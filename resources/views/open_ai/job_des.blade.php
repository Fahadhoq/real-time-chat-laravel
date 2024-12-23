<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Description Generator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .section {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .section h4 {
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: #ffffff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .loading {
            text-align: center;
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Job Description Generator</h1>

        <!-- Job Title Input -->
        <form id="chat-form" class="section mb-4">
            <h4>Enter Job Title</h4>
            <div class="mb-3">
                <label for="content" class="form-label">Job Title:</label>
                <textarea id="content" name="content" rows="3" class="form-control" placeholder="e.g., Software Engineer" required></textarea>
            </div>
            <button type="submit" class="btn btn-custom w-100">Generate Questions</button>
        </form>

        <!-- Response Section -->
        <div id="response-container" class="section"></div>

        <!-- Generated Job Description -->
        <div id="job_description" class="section">
            <!-- <button id="post-to-linkedin" class="btn btn-custom w-100 mt-3 d-none">Post to LinkedIn</button> -->
        </div>

        <form action="{{ route('linkedin.post') }}" method="POST">
            @csrf
            <textarea name="job_description_text" rows="3" placeholder="Write your post here..." required style="display: none;"></textarea>
            <button type="submit" class="btn btn-primary">Post to LinkedIn</button>
        </form>

    </div>

    <script>
        // Setup CSRF Token for Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle form submission to generate questions
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();

            const content = $('#content').val();
            const data = JSON.stringify({ content: content });

            // Show loading message
            $('#response-container').html('<div class="loading">Generating questions...</div>');

            $.ajax({
                url: '/generate/text',
                method: 'POST',
                contentType: 'application/json',
                data: data,
                success: function (response) {
                    const questions = response.text.split("\n\n"); // Assuming response uses double newlines
                    let html = '<form id="options-form" class="question-section">';
                    html += '<h4>Select Options</h4>';

                    questions.forEach((question, index) => {
                        const [q, ...options] = question.split("\n");
                        html += `<p class="fw-bold">${q}</p>`;
                        options.forEach((option, i) => {
                            html += `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_${index}" id="option_${index}_${i}" value="${option}">
                                    <label class="form-check-label" for="option_${index}_${i}">${option}</label>
                                </div>`;
                        });
                    });

                    html += '<button type="submit" class="btn btn-custom mt-3 w-100">Submit Answers</button></form>';
                    $('#response-container').html(html);
                },
                error: function (xhr) {
                    const error = xhr.responseJSON?.error || 'An error occurred';
                    $('#response-container').html('<div class="alert alert-danger">' + error + '</div>');
                }
            });
        });

        // Handle the submission of selected options
        $(document).on('submit', '#options-form', function (e) {
            e.preventDefault();

            const formData = $(this).serializeArray();
            const formattedData = formData.map((field) => ({
                question: `Question ${field.name.replace('question_', '')}`,
                answer: field.value
            }));

            // Show loading message
            $('#job_description').html('<div class="loading">Generating job description...</div>');

            $.ajax({
                url: '/submit/answers',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ questions: formattedData }),
                success: function (response) {
                    const generatedText = response.text; // Extract generated response text
                    $('#job_description').html(
                        '<h4>Generated Job Description:</h4>' +
                        '<div class="alert alert-info">' + generatedText + '</div>'
                    );

                    // Store the description for posting
                    $('#post-to-linkedin').data('description', generatedText);

                    // Set the value of the hidden <textarea> for the LinkedIn post
                   $('textarea[name="job_description_text"]').val(generatedText);

                },
                error: function (xhr) {
                    const error = xhr.responseJSON?.error || 'An error occurred';
                    $('#job_description').html('<div class="alert alert-danger">' + error + '</div>');
                }
            });
        });

        // Handle posting to LinkedIn
        // $(document).on('click', '#post-to-linkedin', function () {
        //     const jobDescription = $(this).data('description');

        //     // Show loading message
        //     $(this).text('Posting...').prop('disabled', true);

        //     $.ajax({
        //         url: '/linkedin/post',
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
        //         },
        //         contentType: 'application/json',
        //         data: JSON.stringify({ description: jobDescription }),
        //         success: function () {
        //             alert('Job description successfully posted to LinkedIn!');
        //             $('#post-to-linkedin').text('Post to LinkedIn').prop('disabled', false);
        //         },
        //         error: function (xhr) {
        //             const error = xhr.responseJSON?.error || 'An error occurred';
        //             alert(error);
        //             $('#post-to-linkedin').text('Post to LinkedIn').prop('disabled', false);
        //         }
        //     });
        // });
    </script>
</body>
</html>
