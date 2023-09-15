<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    const ADMIN = 'Admin';
    const TEACHER = 'Teacher';
    const STUDENT = 'Student';
}
