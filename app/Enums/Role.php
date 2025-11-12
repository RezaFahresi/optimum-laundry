<?php

namespace App\Enums;

enum Role: int
{
    case Admin = 1;
    case Member  = 2;
    case Kasir = 3;
    case Owner = 4;
}
