<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
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
class User extends Authenticatable implements HasTenants , FilamentUser
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
        return true;
    }

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
}
