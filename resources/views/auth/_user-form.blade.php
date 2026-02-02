<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Full Name <span class="text-red-500">*</span>
        </label>
        <input type="text"
               name="name"
               id="name"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter full name"
               value="{{ old('name', $user->name ?? '') }}"
               required
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Email Address <span class="text-red-500">*</span>
        </label>
        <input type="email"
               name="email"
               id="email"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter email address"
               value="{{ old('email', $user->email ?? '') }}"
               required
        >
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
            Role <span class="text-red-500">*</span>
        </label>
        <select name="role"
                id="role"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
                required>
            <option value="">Select a role</option>

            @if(auth()->user()->getRoleRank() == 3)
                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
            @endif

            @if(auth()->user()->getRoleRank() >= 2)
                <option value="endurego_internal" {{ old('role', $user->role ?? '') == 'endurego_internal' ? 'selected' : '' }}>
                    Endurego Internal
                </option>
            @endif

            <option value="client" {{ old('role', $user->role ?? '') == 'client' ? 'selected' : '' }}>
                Client
            </option>
        </select>
        @error('role')
            <p id="role-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @if(!isset($user))
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password <span class="text-red-500">*</span>
        </label>
        <input type="password"
               name="password"
               id="password"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter password"
               required
        >
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
            Confirm Password <span class="text-red-500">*</span>
        </label>
        <input type="password"
               name="password_confirmation"
               id="password_confirmation"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Confirm password"
               required
        >
        @error('password_confirmation')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    @else
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password <span class="text-gray-500 text-xs">(leave blank to keep current)</span>
        </label>
        <input type="password"
               name="password"
               id="password"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter new password"
        >
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
            Confirm Password
        </label>
        <input type="password"
               name="password_confirmation"
               id="password_confirmation"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Confirm new password"
        >
    </div>
    @endif

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
            Phone Number
        </label>
        <input type="text"
               name="phone"
               id="phone"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter phone number"
               value="{{ old('phone', $user->phone ?? '') }}"
        >
        @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

@error('role')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const roleError = document.getElementById('role-error');

        if (roleSelect && roleError) {
            roleSelect.addEventListener('change', function() {
                roleError.style.display = 'none';
            });
        }
    });
</script>
@enderror
