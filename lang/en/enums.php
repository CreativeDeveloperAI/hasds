<?php

return [
    'AssistancePackageStatus' => [
        'active' => 'Active and Ready for Field Distribution',
        'completed' => 'Successfully Distributed and Closed',
        'paused' => 'Temporarily Paused for Operational Reasons',
    ],
    'AssistancePackageType' => [
        'food' => 'Food Basket / Canned Goods / Vegetables',
        'cash' => 'Cash Voucher (ILS)',
        'medical' => 'Medical Supplies and Medicines',
        'clothing' => 'Winter Clothing / Blankets',
    ],
    'CurrentShelterType' => [
        'tent' => 'Fabric Tent / Nylon Tarpaulin in Displacement Camps',
        'shelter_center' => 'Collective Shelter Center (School, Hospital, Public Facility)',
        'rent_apartment' => 'Rented Apartment (Housing Allowance)',
        'host_family' => 'Hosted by Relatives / Friends in a Home',
        'home' => 'Original Citizen Home (Not Completely Destroyed)',
    ],
    'DisabilityType' => [
        'physical' => 'Physical / Motor Disability',
        'visual' => 'Visual Impairment / Blindness',
        'hearing' => 'Hearing Impairment / Deafness',
        'mental' => 'Intellectual / Mental Disability',
        'sensory' => 'Speech and Communication Impairment / Sensory',
        'multiple' => 'Complex Multiple Disabilities',
    ],
    'DistributionStatus' => [
        'pending' => 'Pending / Not Yet Received',
        'delivered' => 'Actually Delivered to Family',
        'cancelled' => 'Cancelled / Replaced in Field',
    ],
    'Gender' => [
        'male' => 'Male',
        'female' => 'Female',
    ],
    'InjurySeverity' => [
        'critical' => 'Very Critical Injury (Highest Priority)',
        'moderate' => 'Moderate Injury (Secondary Priority)',
        'light' => 'Minor Injury (Stable)',
    ],
    'MaritalStatus' => [
        'married' => 'Married',
        'single' => 'Single',
        'widowed' => 'Widowed',
        'divorced' => 'Divorced',
    ],
    'OrganizationStatus' => [
        'pending' => 'Pending Field Verification',
        'approved' => 'Activated and Officially Approved',
        'suspended' => 'Temporarily Suspended (Policy Violation)',
    ],
    'PolicyCategory' => [
        'social' => 'Socio-Demographic Indicators',
        'health' => 'Medical and Health Indicators',
        'shelter' => 'Displacement and Shelter Indicators',
        'financial' => 'Financial and Economic Indicators',
    ],
    'PolicyKey' => [
        'is_displaced' => 'Citizen is displaced in the field',
        'shelter_tent' => 'Current shelter type is a tent',
        'shelter_center' => 'Current shelter type is a shelter center',
        'has_disability' => 'Citizen has special needs',
        'has_chronic_disease' => 'Suffers from chronic diseases',
        'has_recent_injury' => 'Has a recent war injury',
        'vital_status_martyred' => 'Sovereign Status: Martyred',
        'vital_status_missing' => 'Sovereign Status: Missing',
        'gender_female' => 'Citizen is female / potentially family breadwinner',
        'family_large' => 'Large Family (More than 5 members)',
        'no_income' => 'Absolutely no monthly financial income',
    ],
    'ShelterCondition' => [
        'bad' => 'Dilapidated / Unfit for Human Use',
        'acceptable' => 'Acceptable / Needs Light Maintenance',
        'good' => 'Intact / In Very Good Condition',
    ],
    'SurveyStatus' => [
        'active' => 'Active and Field Approved',
        'archived' => 'Archived (Old Historical Record)',
        'conflict' => 'Conflicted (Requires Field Review and Verification)',
    ],
    'VitalStatus' => [
        'alive' => 'Alive',
        'martyred' => 'Martyred (May God have mercy on them)',
        'missing' => 'Missing (Not Found)',
    ],
];
