<select name="company_id" class="custom-select" id="search-select" onchange="this.form.submit()">
    <option value="" selected>Select Company</option>
    @foreach ($companies as $id => $name)
        <option value="{{ $id }}" @if ($id == request()->query('company_id')) selected @endif>{{ $name }}
        </option>
    @endforeach
</select>
