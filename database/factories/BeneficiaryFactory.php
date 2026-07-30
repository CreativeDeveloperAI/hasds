<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\VitalStatus;
use App\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Beneficiary>
 */
class BeneficiaryFactory extends Factory
{
    protected array $maleFirstNames = [
        'محمد', 'أحمد', 'خالد', 'يوسف', 'إبراهيم', 'عمر', 'محمود', 'مصطفى',
        'عبدالله', 'حسام', 'زياد', 'إياد', 'سامي', 'رامي', 'وليد', 'باسل',
        'ماجد', 'نضال', 'فادي', 'حسن',
    ];

    protected array $femaleFirstNames = [
        'فاطمة', 'مريم', 'سارة', 'نور', 'هبة', 'رنا', 'آية', 'دينا',
        'إيمان', 'سلمى', 'ياسمين', 'ليلى', 'هديل', 'أمل', 'رغد', 'دعاء',
        'شذى', 'روان', 'لينا', 'وعد',
    ];

    protected array $familyNames = [
        'النجار', 'أبو مصطفى', 'الشوا', 'البرغوثي', 'حمدان', 'عودة', 'شعبان',
        'الحلبي', 'أبو دقة', 'الفرا', 'المصري', 'أبو عيطة', 'زقوت', 'الأغا',
        'دحلان', 'شاهين', 'الترك', 'أبو جزر', 'حجازي', 'صيام',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement([Gender::Male, Gender::Female]);
        $firstName = $gender === Gender::Male
            ? fake()->randomElement($this->maleFirstNames)
            : fake()->randomElement($this->femaleFirstNames);
        $fatherName = fake()->randomElement($this->maleFirstNames);
        $familyName = fake()->randomElement($this->familyNames);
        $dateOfBirth = fake()->dateTimeBetween('-85 years', '-18 years')->format('Y-m-d');

        return [
            'national_id' => fake()->unique()->numerify('#########'),
            'full_name' => "{$firstName} {$fatherName} {$familyName}",
            'gender' => $gender,
            'date_of_birth' => $dateOfBirth,
            // كلمة السر = تاريخ الميلاد، بنفس منطق تسجيل الدخول الفعلي (CustomBeneficiaryLogin/CreateBeneficiary)
            'password' => Hash::make($dateOfBirth),
            'personal_phone' => sprintf('+970 5%d-%03d-%04d', fake()->numberBetween(0, 9), fake()->numberBetween(0, 999), fake()->numberBetween(0, 9999)),
            'marital_status' => fake()->randomElement([
                MaritalStatus::Married,
                MaritalStatus::Married,
                MaritalStatus::Married,
                MaritalStatus::Single,
                MaritalStatus::Single,
                MaritalStatus::Widowed,
                MaritalStatus::Divorced,
            ]),
            'vital_status' => fake()->randomElement([
                ...array_fill(0, 85, VitalStatus::Alive),
                ...array_fill(0, 10, VitalStatus::Martyred),
                ...array_fill(0, 5, VitalStatus::Missing),
            ]),
        ];
    }

    /**
     * حالة صريحة: مستفيد على قيد الحياة
     */
    public function alive(): static
    {
        return $this->state(fn (array $attributes) => [
            'vital_status' => VitalStatus::Alive,
        ]);
    }

    /**
     * حالة صريحة: مستفيد ذكر
     */
    public function male(): static
    {
        return $this->state(function (array $attributes) {
            $firstName = fake()->randomElement($this->maleFirstNames);
            $fatherName = fake()->randomElement($this->maleFirstNames);
            $familyName = fake()->randomElement($this->familyNames);

            return [
                'gender' => Gender::Male,
                'full_name' => "{$firstName} {$fatherName} {$familyName}",
            ];
        });
    }

    /**
     * حالة صريحة: مستفيدة أنثى
     */
    public function female(): static
    {
        return $this->state(function (array $attributes) {
            $firstName = fake()->randomElement($this->femaleFirstNames);
            $fatherName = fake()->randomElement($this->maleFirstNames);
            $familyName = fake()->randomElement($this->familyNames);

            return [
                'gender' => Gender::Female,
                'full_name' => "{$firstName} {$fatherName} {$familyName}",
            ];
        });
    }
}
