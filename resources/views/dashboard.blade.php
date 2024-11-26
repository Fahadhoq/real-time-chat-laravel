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
        
    <style>
        .real-time-msg {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: #333;
        }
        .chat-box {
            width: 50%;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #f9f9f9;
        }

        .messages {
            height: 300px;
            padding: 15px;
            overflow-y: auto;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }

        .message {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 6px;
            display: inline-block;
            max-width: 70%;
            clear: both;
        }

        .message.auth-user {
            background: #c8e6c9;
            float: right;
            text-align: right;
        }

        .message.other-user {
            background: #e1f5fe;
            float: left;
            text-align: left;
        }

        .message b {
            color: #0277bd;
            display: block;
            margin-bottom: 5px;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            background: #f9f9f9;
        }

        .chat-input input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .chat-input button {
            padding: 8px 15px;
            background: #0277bd;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .chat-input button:hover {
            background: #01579b;
        }
    </style>


        

        <p><b>Real-Time How Many User Join:</b> <span id="user-join"></span></p>
        <p><b>Real-Time New Joiner:</b> <span id="new-joiner"></span></p>
        <p><b>Real-Time New Leaver:</b> <span id="new-leaver"></span></p>
        
        <div class="chat-box">
           <p class="real-time-msg"><b>Real-Time Msg</b></p>

            <div id="chat-messages" class="messages"></div>
            <div class="chat-input">
                <input type="text" id="msg" placeholder="Type your message..." />
                <button id="send_msg">Send</button>
            </div>
        </div>


     
        <!-- Inline script -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                //socket msg start
                    //public channel
                    // if (typeof window.Echo !== 'undefined') {
                    //     window.Echo.channel('test-channel')
                    //         .listen('TestData', (data) => {
                    //             document.getElementById('messeng_data').innerHTML = data.data;
                    //         });
                    // } else {
                    //     console.log('Echo is not defined in the Blade script.');
                    // }

                    //private channel
                    // if (typeof window.Echo !== 'undefined') {
                    //     window.Echo.private('test-PrivateChannel')
                    //         .listen('TestData', (data) => {
                    //             document.getElementById('messeng_data').innerHTML = data.data;
                    //         });
                    // } else {
                    //     console.log('Echo is not defined in the Blade script.');
                    // }

                    //presence channel
                    // if (typeof window.Echo !== 'undefined') {
                    //     window.Echo.join('test-PresenceChannel')
                    //         .here((user)=>{
                    //             document.getElementById('user-join').innerHTML = user.length;
                    //         })
                    //         .joining((user)=>{
                    //             document.getElementById('new-joiner').innerHTML = user.name;
                    //         })
                    //         .leaving((user)=>{
                    //             document.getElementById('new-leaver').innerHTML = user.name;
                    //         })
                    //         .listen('TestData', (data) => {
                    //             console.log('Broadcasted Data:', data); 
                                
                    //             // Append the new message
                    //             document.getElementById('messages').innerHTML += `<li><b>${data.user}:</b> ${data.messeng}</li>`;
                    //         });
                    // } else {
                    //     console.log('Echo is not defined in the Blade script.');
                    // }
                //socket msg end


                // real time chat
                const authUserId = "{{ auth()->id() }}"; // Get the authenticated user ID
               
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.join('test-PresenceChannel')
                        .here((user)=>{
                            document.getElementById('user-join').innerHTML = user.length;
                        })
                        .joining((user)=>{
                            document.getElementById('new-joiner').innerHTML = user.name;
                        })
                        .leaving((user)=>{
                            document.getElementById('new-leaver').innerHTML = user.name;
                        })
                        .listen('TestData', (data) => {
                            const isAuthUser = data.user.id == authUserId; // Check if the sender is the auth user
                            appendMessage(data.user, data.messeng, isAuthUser);
                        });
                }

                // Function to append messages
                function appendMessage(user, message, isAuthUser) {
                    const chatMessages = document.getElementById('chat-messages');
                    const alignmentClass = isAuthUser ? 'auth-user' : 'other-user';
                    const newMessage = `
                        <div class="message ${alignmentClass}">
                            <b>${user.name}</b>
                            <span>${message}</span>
                        </div>
                    `;
                    chatMessages.innerHTML += newMessage;
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll
                }
 
                //call api
                 $(document).on('click', '#send_msg', function () {
                    var msg = $('#msg').val();
                    var url = '/webSocket/msg';

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    });

                    $.ajax({
                        url: url,
                        type: 'post',
                        data: { msg: msg },
                        success: function () {
                            $('#msg').val(''); // Clear the input
                        }
                    });
                });

            });
        </script>
    </div>
</x-app-layout>

