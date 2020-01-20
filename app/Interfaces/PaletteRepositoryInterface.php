<?php

namespace App\Interfaces;

interface PaletteRepositoryInterface
{
    public function get();
    public function userSimplePalettes();
    public function userAdvancedPalettes();
    public function adminSimplePalettes();
    public function adminAdvancedPalettes();
    public function userPalettes($type);
    public function adminPalettes($type);
    public function formatPalettes($categories);
    public function getUserPalettes();
    public function getAdminPalettes();
}
