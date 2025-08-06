<?php

namespace App\Enum;

enum Category: string
{
    case DRINKS = 'Boissons';
    case GRAINS_AND_STARCHES = 'Céréales & Féculents';
    case FRUITS_AND_VEGETABLES = 'Fruits & Légumes';
    case DAIRY_PRODUCTS = 'Produits Laitiers';
    case MEAT_AND_FISH = 'Viandes & Poissons';
    case SWEET_PRODUCTS = 'Produits Sucrés';
    case OTHER = 'Autre';
}
