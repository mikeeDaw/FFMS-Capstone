@if (session()->has('message'))
    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class=" fixed-top align-items-center flex-column px-4 py-3" 
    style="width: 350px;margin: auto;text-align: center;display: flex;background-color: #ffffff;top: 60px;border: 1px solid #60ffc2;
    box-shadow: rgb(5 255 46 / 72%) 0px 0px 40px -16px;">

        <div class="d-flex flex-row justify-content-center align-items-center gap-2 mt-2">

            <div style="flex-grow: 1; background: #cbcbcb; height: 1px;"> </div>
            <ion-icon name="checkmark-circle-outline" style="width: 60px;
            height: 60px; color: #6dd784;"></ion-icon>
            <div style="flex-grow: 1; background: #cbcbcb; height: 1px;"> </div>

        </div>

        <p class="mt-4 mb-1 fw-semibold">
            {{ session('message') }}
        </p>

        @if(str_starts_with(session('message'), "You"))
        <p class="mb-3" style="font-size: 14px; color: #747474;"> Thank you. </p>
        @endif
        
    </div>
@endif