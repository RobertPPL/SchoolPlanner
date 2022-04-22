<div>
    <form action="{{ $storeRoute }}" method="POST" class="form">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="name" placeholder="Wprowadź nazwę nowego elementu" maxlength="100" class="form-control @error('name') is-invalid @enderror">
            <input type="submit" value="Dodaj" class="btn btn-secondary">
        </div>
    </form>
</div>