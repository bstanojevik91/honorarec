<?php

namespace App\Models;

use App\Support\CategoryName;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = ['name'];

    protected static function booted(): void
    {
        static::saving(function (JobCategory $category): void {
            $category->name = CategoryName::display($category->name);
            $category->normalized_name = CategoryName::normalize($category->name);
        });
    }

    public static function canonicalName(string $name): ?string
    {
        return static::query()->where('normalized_name', CategoryName::normalize($name))->value('name');
    }
}
