<?php

namespace App\Policies;

use App\Models\Produk;
use App\Models\User;

class ProdukPolicy
{
    /**
     * Helper untuk mengambil nama role dalam bentuk lowercase
     */
    private function getRoleName(User $user): string
    {
        // Mendukung relasi $user->role->name ATAU kolom biasa $user->role
        $role = is_object($user->role) ? $user->role->name : $user->role;
        return strtolower((string)$role);
    }

    public function viewAny(User $user): bool
    {
        return in_array($this->getRoleName($user), ['admin', 'kasir']);
    }

    public function view(User $user, Produk $produk): bool
    {
        return in_array($this->getRoleName($user), ['admin', 'kasir']);
    }

    public function create(User $user): bool
    {
        return $this->getRoleName($user) === 'admin';
    }

    public function update(User $user, Produk $produk): bool
    {
        return $this->getRoleName($user) === 'admin';
    }

    public function delete(User $user, Produk $produk): bool
    {
        return $this->getRoleName($user) === 'admin';
    }

    public function restore(User $user, Produk $produk): bool
    {
        return false;
    }

    public function forceDelete(User $user, Produk $produk): bool
    {
        return false;
    }
}
