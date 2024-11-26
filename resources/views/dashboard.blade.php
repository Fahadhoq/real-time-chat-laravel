<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class='text-center my-4'>
        <hr class='my-4'>
        <span class='w-3/5 mx-auto mt-4'>
            <div>
                <h1>Event</h1>
                <a href="{{ route('order.ship') }}">
                    <button class="ms-3">
                    Click or order ship
                    </button>
                </a>
            </div>
        </span>
    </div>

    <div>
        <p><b>Real-Time Msg:</b> <span id="test-data"></span></p>

        <div>
            <input class="form-control" type="text" name="msg" id="msg"> 
            <button class="ms-3" id="send_msg">
                Send Msg
            </button>
        </div>
     
        <!-- Inline script -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                //socket msg start
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.channel('test-channel')
                        .listen('TestData', (data) => {
                            document.getElementById('test-data').innerHTML = data.data;
                        });
                } else {
                    console.log('Echo is not defined in the Blade script.');
                }
                //socket msg end
 
                //call api
                $(document).on('click', '#send_msg', function(){
                    var csrf_token = $('input[name=_token]').val();  
                    var msg = $('#msg').val();
                    console.log(msg);
                    var url = '/webSocket/msg'; 

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });
                    
                    $.ajax({  
                            url: url,  
                            type: 'get',  
                            data: {
                                msg : msg,
                                "_token": "{{ csrf_token() }}"
                                },  
                            success:function(data){  
                                console.log('success'); 
                            }  
                    });  
                });
            });
        </script>
    </div>
</x-app-layout>

