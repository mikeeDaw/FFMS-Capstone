@if (session()->has('errMsg'))
    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class=" fixed-top align-items-center flex-column px-4 py-3" 
    style="width: 350px;margin: auto;text-align: center;display: flex;background-color: #ffffff;top: 60px;border: 1px solid #e84646;
    box-shadow: rgb(222 81 71) 0px 0px 40px -16px;">

        <div class="d-flex flex-row justify-content-center align-items-center gap-2 mt-2">

            <div style="flex-grow: 1; background: #cbcbcb; height: 1px;"> </div>
            <ion-icon name="close-circle-outline" style="width: 60px;
            height: 60px; color: #d7746d;"></ion-icon>
            <div style="flex-grow: 1; background: #cbcbcb; height: 1px;"> </div>

        </div>

        <p class="mt-4 mb-1 fw-semibold">
            {{ session('errMsg') }}
        </p>
        
    </div>
@endif