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
               value="{{ old('name', $client->name ?? '') }}"
               required
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Email Address
        </label>
        <input type="email"
               name="email"
               id="email"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter email address"
               value="{{ old('email', $client->email ?? '') }}"
        >
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
            Phone Number
        </label>
        <input type="text"
               name="phone"
               id="phone"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter phone number"
               value="{{ old('phone', $client->phone ?? '') }}"
        >
        @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="company" class="block text-sm font-medium text-gray-700 mb-1">
            Company
        </label>
        <input type="text"
               name="company"
               id="company"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter company name"
               value="{{ old('company', $client->company ?? '') }}"
        >
        @error('company')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-1">
            Tax Number
        </label>
        <input type="text"
               name="tax_number"
               id="tax_number"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
               placeholder="Enter tax number"
               value="{{ old('tax_number', $client->tax_number ?? '') }}"
        >
        @error('tax_number')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
            Address
        </label>
        <textarea name="address"
                  id="address"
                  rows="3"
                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
                  placeholder="Enter address">{{ old('address', $client->address ?? '') }}</textarea>
        @error('address')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
            Notes
        </label>
        <textarea name="notes"
                  id="notes"
                  rows="4"
                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-150"
                  placeholder="Enter any additional notes">{{ old('notes', $client->notes ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
