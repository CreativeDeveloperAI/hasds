<?php

namespace App\Filament\Resources\ScoringPolicies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScoringPolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('messages.ui_9fbb89fc'))
                    ->description(__('messages.ui_1ca5f0c3'))
                    ->schema([
                        TextInput::make('policy_name')
                            ->label(__('messages.ui_2a10d32d'))
                            ->required()
                            ->placeholder(__('messages.ui_5032547a')),
                        Select::make('policy_key')
                            ->label(__('messages.ui_203d9b74'))
                            ->required()
                            ->options([
                                // مؤشرات النزوح والمأوى
                                'is_displaced' => __('messages.ui_d911aa74'),
                                'shelter_tent' => __('messages.ui_db8f2b17'),
                                'shelter_center' => __('messages.ui_278a5420'),

                                // مؤشرات صحية وطبية (Pivot)
                                'has_disability' => __('messages.ui_c1c3ebdf'),
                                'has_chronic_disease' => __('messages.ui_79d12fb0'),
                                'has_recent_injury' => __('messages.ui_698e5e6f'),

                                // مؤشرات سيادية (Sovereign Beneficiary)
                                'vital_status_martyred' => __('messages.ui_bf821437'),
                                'vital_status_missing' => __('messages.ui_03c47f01'),
                                'gender_female' => __('messages.ui_f7117bfb'),

                                // مؤشرات مركبة ديموغرافية واقتصادية
                                'family_large' => __('messages.ui_9e2d1605'),
                                'no_income' => __('messages.ui_be2d80d6'),
                            ])
                            ->disabled(fn ($record) => $record !== null) // حظر التغيير بعد الإنشاء للحفاظ على استقرار الكود
                            ->helperText(__('messages.ui_fbc73678')),

                        Select::make('category')
                            ->label(__('messages.ui_3ea61794'))
                            ->options([
                                'social' => __('messages.ui_d37e46c6'),
                                'health' => __('messages.ui_58cf2eca'),
                                'shelter' => __('messages.ui_1a57b530'),
                                'financial' => __('messages.ui_3262c091'),
                            ])
                            ->required(),
                        TextInput::make('points_weight')
                            ->label(__('messages.ui_cb36e525'))
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->placeholder(__('messages.ui_9b0bb84c')),
                        Toggle::make('is_active')
                            ->label(__('messages.ui_376b8ce0'))
                            ->default(true),
                    ])->columns(4),
            ]);
    }
}
