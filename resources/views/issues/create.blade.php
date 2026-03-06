<x-app-layout>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold mb-4">Submit Issue</h2>

        <form method="POST" action="/issues">
            @csrf

            <div class="mb-4">
                <label>Title</label>
                <input type="text" name="title" class="border p-2 w-full" required>
            </div>

            <div class="mb-4">
                <label>Description</label>
                <textarea name="description" class="border p-2 w-full" required></textarea>
            </div>

            <div class="mb-4">
                <label>Priority</label>
                <select name="priority" class="border p-2 w-full">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    Submit Issue
</button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">
                Submit Issue
            </button>
        </form>
    </div>

</x-app-layout>