<div class="row">
    <form method="POST" action="{{route("converter.convert")}}">
        @csrf
        
        @if (Session::has('Converted'))
            <div class="border-0 d-flex justify-content-center align-items-center">
                Converted value = {{ Session::get('Converted') }}
            </div>
        @endif
        <div class="form-control border-0 d-flex justify-content-center align-items-center">
            <label for="sum" class="me-3">Сумма</label>
            <input id="sum" name="sum" type="text" class="@error('sum') is-invalid @enderror" value="{{Session::has('sum') ? Session::get('sum') : 0}}">
            @error('sum')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-control border-0 d-flex justify-content-center align-items-center">
            <label for="currency" class="me-3">From</label>
            {{Form::select("currency_from", $currencies ?? [], Session::has('currency_from') ? Session::get('currency_from') : "Ukrainian Hryvnia")}}
            @error('currency_from')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-control border-0 d-flex justify-content-center align-items-center">
            <label for="currency" class="me-3">To</label>
            {{Form::select("currency_to", $currencies ?? [], Session::has('currency_to') ? Session::get('currency_to') : "Ukrainian Hryvnia")}}
            @error('currency_to')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-control border-0  d-flex justify-content-center align-items-center">
            <button type="submit" class="btn btn-primary col-1">Ok</button>
        </div>
    </form>
</div>