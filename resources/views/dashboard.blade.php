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

        .offline-status{
            color: red;
        }
        .online-status{
            color: green;
        }

        .user-list.active {
            background-color: #e0f7fa;
            font-weight: bold;
        }

        #selected-user {
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
    </style>


        

        <!-- <p><b>Real-Time How Many User Join:</b> <span id="user-join"></span></p>
        <p><b>Real-Time New Joiner:</b> <span id="new-joiner"></span></p>
        <p><b>Real-Time New Leaver:</b> <span id="new-leaver"></span></p> -->
        
        <div style="width: 70%; margin: auto; display: flex; border: 1px solid #ddd; height: 500px; border-radius: 8px; overflow: hidden;">
            <!-- Sidebar User List -->
            <div style="width: 30%; border-right: 1px solid #ddd; overflow-y: auto; background-color: #f9f9f9;">
                <h3 style="text-align: center; padding: 10px; background-color: #4CAF50; color: white; margin: 0;">Users</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($users as $user)
                    <li style="padding: 10px 15px; border-bottom: 1px solid #ddd; cursor: pointer; display: flex; align-items: center;" class='user-list' data-id="{{$user->id}}">
                        <div style="width: 35px; height: 35px; border-radius: 50%; background-color: #ccc; text-align: center; line-height: 35px; font-weight: bold; margin-right: 10px;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <span >{{ $user->name }}</span>
                        <b><sup id="{{$user->id}}-status" class="offline-status">Offline</sup></b>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Chat Box -->
            <div style="width: 70%; display: flex; flex-direction: column;">
                <!-- Header -->
                <div style="background-color: #4CAF50; padding: 10px; color: white; text-align: center;">
                    <b>Messenger</b>
                </div>

                 <!-- Selected User Header -->
                <div id="selected-user" style="padding: 10px; background-color: #eaeaea; text-align: center; font-weight: bold; display: none;">
                    Chat with: <span id="selected-user-name">User Name</span>
                </div>

                <div id="msg_load" style="padding: 10px; background-color: #eaeaea; text-align: center; font-weight: bold; display: none;">
                    Msg Load Time: <span id="msg_load_time"></span>
                </div>

                <!-- Messages -->
                <div id="chat-messages" style="flex: 1; padding: 10px; overflow-y: auto; background-color: #f4f4f4;">
                    <!-- Messages will appear here -->
                </div>

                <!-- Input -->
                <div style="padding: 10px; background-color: #fff; display: flex; gap: 10px; align-items: center;">
                    <input type="text" id="msg" placeholder="Type your message..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" />
                    <button id="send_msg" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Send
                    </button>
                </div>
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
                var sender_id = "{{ auth()->id() }}"; 
                var receiver_id; 

                $('#send_msg').prop('disabled', true).css({
                    opacity: 0.5,
                    cursor: 'not-allowed',
                });

                $('.user-list').click(function(){
                    receiver_id = $(this).attr('data-id');
                    // Remove active class from all users
                    $('.user-list').removeClass('active');

                    // Add active class to the clicked user
                    $(this).addClass('active');

                    // Get the selected user's name
                    const selectedUserName = $(this).find('span').text();

                    // Update the selected user header
                    $('#selected-user-name').text(selectedUserName);

                    // Show the selected user header
                    $('#selected-user').show();
                    

                    // Enable the Send button
                    if (receiver_id) {
                        $('#send_msg').prop('disabled', false).css({
                            opacity: 1,
                            cursor: 'pointer',
                        });
                    }

                    //show old msg
                    var url = '/old/msg';

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    });

                    $.ajax({
                        url: url,
                        type: 'get',
                        data: { receiver_id:receiver_id, sender_id:sender_id },
                        success: function (response) {
                            $('#msg_load').show();
                            $('#msg_load_time').text(response.execution_time);
                            // const messages = JSON.parse(response);
                            appendOldMessage(response.chats);
                        }
                    });
                });

                function appendOldMessage(chats) {
                    const chatMessages = document.getElementById('chat-messages');
                    chatMessages.innerHTML = '';
                    chats.forEach(chat => {
                        const alignmentClass = (chat.sender_id == authUserId*1) ? 'auth-user' : 'other-user';
                        const senderName =  chat.sender.name;
                        
                        const oldMessage = `
                            <div class="message ${alignmentClass}">
                                <b>${senderName}</b>
                                <span>${chat.msg}</span>
                            </div>
                        `;
                        chatMessages.innerHTML += oldMessage;
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to the bottom
                }


                if (typeof window.Echo !== 'undefined') {
                    window.Echo.join('test-PresenceChannel')
                        .here((users) => {
                            users.forEach((user) => {
                                if (sender_id != user.id) {
                                    $('#' + user.id + '-status').removeClass('offline-status');
                                    $('#' + user.id + '-status').addClass('online-status');
                                    $('#' + user.id + '-status').text('online');
                                }
                            });
                        })
                        .joining((user) => {
                            $('#' + user.id + '-status').removeClass('offline-status');
                            $('#' + user.id + '-status').addClass('online-status');
                            $('#' + user.id + '-status').text('online');
                        })
                        .leaving((user) => {
                            $('#' + user.id + '-status').addClass('offline-status');
                            $('#' + user.id + '-status').removeClass('online-status');
                            $('#' + user.id + '-status').text('offline');
                        })
                        .listen('TestData', (data) => {
                            const isAuthUser = data.user.id == authUserId; // Check if the sender is the auth user
                            
                            if ((receiver_id*1 == data.sender_id) && (sender_id*1 == data.receiver_id)){
                                appendMessage(data.user, data.messeng, isAuthUser , data.chats);
                            }else if ((receiver_id*1 == data.receiver_id) && (sender_id*1 == data.sender_id)){
                                appendMessage(data.user, data.messeng, isAuthUser , data.chats);
                            }
                        });
                }


                // Function to append messages
                function appendMessage(user, message, isAuthUser,chats) {
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
                        data: { msg: msg , receiver_id:receiver_id, sender_id:sender_id },
                        success: function () {
                            $('#msg').val(''); // Clear the input
                        }
                    });
                });

            });
        </script>
    </div>
</x-app-layout>

