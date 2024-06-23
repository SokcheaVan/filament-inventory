<?php

namespace App\Policies;

use App\Models\CustomerBankAccount;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerBankAccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return !$user->role || in_array('customer_bank_account-read', $user->role->permissions);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CustomerBankAccount $customer_bank_account): bool
    {
        return !$user->role || in_array('customer_bank_account-read', $user->role->permissions);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->role || in_array('customer_bank_account-create', $user->role->permissions);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CustomerBankAccount $customer_bank_account): bool
    {
        return !$user->role || in_array('customer_bank_account-update', $user->role->permissions);
    }

    /*
     * Determine whether the user can update the model.
     */
    public function export(User $user, CustomerBankAccount $customer_bank_account): bool
    {
        return !$user->role || in_array('customer_bank_account-export_excel', $user->role->permissions);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CustomerBankAccount $customer_bank_account): bool
    {
        return !$user->role || in_array('customer_bank_account-delete', $user->role->permissions);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CustomerBankAccount $customer_bank_account): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CustomerBankAccount $customer_bank_account): bool
    {
        return false;
    }
}
