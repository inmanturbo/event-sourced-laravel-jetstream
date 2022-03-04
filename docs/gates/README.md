```php
        Gate::define('manageSuperAdmin', function (User $user, User $userBeingManaged) {
            
            return $user->isSuperAdmin() || !$userBeingManaged->isSuperAdmin();
        });
```