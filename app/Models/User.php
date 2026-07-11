<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable(['name', 'email', 'password', 'organization_id', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasTenants , FilamentUser ,HasDefaultTenant
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    /**
     * جلب المؤسسات التي ينتمي إليها المستخدم (لوحة المؤسسة ستعتمد على هذه الدالة)
     */
    public function getTenants(Panel $panel): Collection
    {
        return collect([$this->organization])->filter();
    }


    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            // لوحة الأدمن السيادية: مخصصة فقط لمسؤولي النظام المركزي (الذين ليس لديهم مؤسسة مرتبطة)
            return $this->organization_id === null && $this->is_active;
        }

        if ($panel->getId() === 'organization') {
            // لوحة المؤسسة: مخصصة فقط للشركاء والباحثين الميدانيين التابعين للمؤسسات الشريكة
            return $this->organization_id !== null && $this->is_active;
        }

        if ($panel->getId() === 'beneficiary') {
            // لوحة المستفيدين الموحدة: محظورة تماماً على موظفي الإدارة والشركاء (يتم تسجيل دخول المستفيد عبر حارسه المخصص ونموذج Beneficiary)
            return false;
        }

        return false;    }

    /**
     * التحقق مما إذا كان المستخدم يملك صلاحية دخول هذه المؤسسة بالذات
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organization_id === $tenant->id && $this->is_active;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    /**
     * ميثاق HasDefaultTenant: توجيه المستخدم تلقائياً لمنظمته فور تسجيل الدخول
     * وتجنب شاشة اختيار المنظمة أو حلقات التوجيه اللانهائية
     */
    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->organization;
    }
}
