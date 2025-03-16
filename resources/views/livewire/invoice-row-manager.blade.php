<div :title="__('Invoice Row Management')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header and Add Button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Invoice Row Management</h1>
            <button wire:click="showCreateForm" class="bg-blue-600 text-gray-900 px-4 py-2 rounded-lg hover:bg-blue-700 dark:text-white">Add Invoice Row</button>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Invoice Row Table -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-gray-900 dark:text-white">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th wire:click="sort('id')" class="px-6 py-3 cursor-pointer">ID</th>
                            <th wire:click="sort('invoice_id')" class="px-6 py-3 cursor-pointer">Invoice</th>
                            <th wire:click="sort('client_appointment_id')" class="px-6 py-3 cursor-pointer">Appointment</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->invoiceRows as $invoiceRow)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-6 py-4">{{ $invoiceRow->id }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $invoiceRow->invoice->number }}</td>
                                <td class="px-6 py-4">{{ $invoiceRow->client_appointment_id }}</td>
                                <td class="px-6 py-4">
                                    <button wire:click="showEditForm('{{ $invoiceRow->id }}')" class="text-blue-600 hover:underline">Edit</button>
                                    <button wire:click="delete('{{ $invoiceRow->id }}')" wire:confirm="Are you sure you want to delete this invoice row?" class="text-red-600 hover:underline ml-4">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No invoice rows found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->invoiceRows->links() }}
            </div>
        </div>

        <!-- Form Modal -->
        @if ($showForm)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 dark:bg-black dark:bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ $isEditing ? 'Edit Invoice Row' : 'Add Invoice Row' }}</h2>
                    <form wire:submit="save" class="space-y-4">
                        <div>
                            <label for="invoice_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Invoice</label>
                            <select wire:model="invoice_id" id="invoice_id" class="mt-1 w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Invoice</option>
                                @foreach ($invoices as $id => $number)
                                    <option value="{{ $id }}">{{ $number }}</option>
                                @endforeach
                            </select>
                            @error('invoice_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="client_appointment_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Appointment</label>
                            <select wire:model="client_appointment_id" id="client_appointment_id" class="mt-1 w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Appointment</option>
                                @foreach ($appointments as $id => $appointmentId)
                                    <option value="{{ $id }}">{{ $appointmentId }}</option>
                                @endforeach
                            </select>
                            @error('client_appointment_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end gap-2">
                            <button wire:click="$set('showForm', false)" class="bg-gray-500 text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-600 dark:bg-gray-500 dark:text-gray-900">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-gray-900 px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-600 dark:text-gray-900">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>