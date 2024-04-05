<div>
    <h1>mobile store</h1>

    <form action="">
        <!--input wire:model.live="barcode" type="text" placeholder="업체비엘"><br-->
        <div>
            @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif
        </div>
        <input wire:model.live="barcode" type="text" placeholder="업체비엘"><br>
        @if($supplierSW)
        <select wire:model="supplier">
            <option value="">업체</option>
            @foreach (\App\Models\supplier::all() as $spl)
            <option value="{{ $spl->id }}">{{ $spl->supplier_name }}</option>
            @endforeach
        </select><br>
        @endif
        <select wire:model="location">
            <option value="">위치</option>
            @foreach (\App\Models\cargo_location::all() as $location)
            <option value="{{ $location->id }}">{{ $location->location_name }}</option>
            @endforeach
        </select><br>
        <select wire:model="packing">
            <option value="">포장</option>
            @foreach (\App\Models\cargo_packing::where('is_visible',1)->get() as $packing)
            <option value="{{ $packing->id }}">{{ $packing->packing_name }}</option>
            @endforeach
        </select><br>
        <input wire:model="cargoPrice" type="text" placeholder="운임"><br>
        <input wire:model="weight" type="text" placeholder="중량"><br>
    </form>

    <br>
    <button wire:click="storeOrder">입고하기</button>

</div>