
<div>

    <div class="wrapper w-full md:max-w-5xl mx-auto pt-20 px-4">
        <p>Usuario: {{ $this->user}}<p>
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold">View Product</h1>
        </div>
        {{ $this->productInfolist }}
        <div>
            <button wire:click="buy" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Buy Product
            </button>
        </div>
    </div>



</div>

