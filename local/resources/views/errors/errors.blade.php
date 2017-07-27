@if (isset($errors) && $errors->any())
    <div class="alert alert-danger" id="error_display">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif