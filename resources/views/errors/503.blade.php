<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode :: {{ $exception->getMessage() ?? 503 }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .zoom-image {
            @apply absolute inset-0 overflow-hidden;
        }

        .image-wrap {
            @apply absolute -top-[350px] -bottom-[350px] -start-[100px] -end-[100px] min-w-full w-auto min-h-full h-auto overflow-hidden m-auto;
            animation: 100s ppb_kenburns linear infinite alternate;
        }

        @keyframes move {
            0% {
                transform-origin: bottom;
                transform: scale(1);
            }

            100% {
                transform: scale(1.4);
            }
        }

        @keyframes ppb_kenburns {
            0% {
                transform: scale(1.3) translate(-10%, 10%);
            }

            25% {
                transform: scale(1) translate(0, 0);
            }

            50% {
                transform: scale(1.3) translate(10%, 10%);
            }

            75% {
                transform: scale(1) translate(0, 0);
            }

            100% {
                transform: scale(1.3) translate(-10%, 10%);
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    }
                }
            }
        }
    </script>
</head>

<body>

    <div class="page-wrapper">
        <main class="page-content bg-gray-50 dark:bg-slate-800">
            <section class="md:h-screen py-36 flex items-center justify-center relative overflow-hidden zoom-image">
                <div
                    class="absolute inset-0 image-wrap z-1 bg-[url('/static/images/bg/01.jpg')] bg-no-repeat bg-center bg-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black z-2" id="particles-snow"></div>
                <div class="container relative z-3 text-center">
                    <div class="grid grid-cols-1">
                        <img src="/static/images/icon/02.png" class="mx-auto" alt="">
                        <h1 class="text-white mb-6 mt-8 md:text-5xl text-3xl font-bold">We'll be back soon!</h1>
                        <p class="text-white/70 text-lg max-w-xl mx-auto">Our site is currently undergoing maintenance.
                            Please check back shortly!</p>
                        <p class="text-white/70 text-lg max-w-xl mx-auto">{{ $exception->getMessage() }}</p>
                    </div><!--end grid-->

                </div><!--end container-->
            </section><!--end section -->
        </main>
    </div>
</body>

</html>
