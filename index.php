<!DOCTYPE html>
<html class="bg-black" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extract URL (image , Email , Phone)</title>
    <!--  Link CSS  -->
    <link rel="stylesheet" href="/assets/css/output.css">
    <!--  Link Font Awesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="">
    <div class="md:w-[90%] border border-white m-auto select-none p-[100px_0px_10px_0px]">
        <div class="md:w-[70%] w-full p-5 md:p-0 m-auto">
            <h1 class="text-center text-white text-[2.5em] md:font-bold font-extrabold pb-[50px]">Extract URL For Get Email, PhoneNumber and Images Not Duplicate.</h1>
            <form id="formData" class="flex w-full md:gap-0 gap-[30px] flex-wrap md:justify-between justify-center items-center ">
                <div class="relative md:w-[70%]  w-[100%]">
                    <div class=" border border-white bg-black justify-center shadow-sm flex items-center overflow-hidden">
                        <span class="text-gray-400 text-sm pl-4 pr-1 ">http://</span>
                        <input required type="text" name="url" placeholder="site.com" class="w-full bg-transparent py-3 px-0 text-gray-300 placeholder:text-sm outline-none placeholder-gray-400 text-sm">
                        <div class="p-3 text-gray-400 border-s-2 border-white cursor-pointer hover:text-gray-300 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn border border-white relative text-white h-[50px] md:w-[18%] w-[50%]">
                    <span>Extract</span>
                    <i class="fa-solid absolute right-5 fa-arrow-right border duration-[0.5s] rounded-full p-[10px]"></i>
                </button>
                <button type="submit" class="btn border border-white relative text-white h-[50px] md:w-[18%] w-[50%]">
                    <span>Clear</span>
                    <i class="fa-solid absolute right-5 fa-arrow-right border duration-[0.5s] rounded-full p-[10px]"></i>
                </button>
            </form>
        </div>
    </div>
    <!-- Display from File -->
    <section class="md:w-[90%]  m-auto">
        <div class="w-full justify-center flex items-center">
            <button type="button" class="btn-Show" id="showPhoto">
                <h1> All Photo</h1>
            </button>
            <button type="button" class="btn-Show" id="showEmail">
                <h1>All Email</h1>
            </button>
            <button type="button" class="btn-Show" id="showPhone">
                <h1>All Phone Number</h1>
            </button>
        </div>
        <div id="displayData" class="mt-8 md:border pb-[50px] mb-[100px] rounded flex justify-center items-center flex-wrap"></div>
    </section>
</body>
<!--  JS  -->
<script src="/assets/js/app.js"></script>

</html>