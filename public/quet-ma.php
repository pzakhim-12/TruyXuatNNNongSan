<?php include 'header.php'; ?>

<main class="flex-1 flex items-center justify-center p-6">
    <div class="max-w-lg w-full bg-white p-8 rounded-3xl border border-gray-200 shadow-lg text-center relative overflow-hidden">
        <i class="fa-brands fa-ethereum absolute -right-10 -bottom-10 text-9xl text-gray-50/50"></i>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2 relative z-10">Kiểm tra Blockchain</h2>
        <p class="text-sm text-gray-500 mb-8 relative z-10">Đưa mã QR trên bao bì sản phẩm vào khung hình để xác thực nguồn gốc.</p>

        <div class="relative w-64 h-64 mx-auto border-4 border-dashed border-gray-300 rounded-3xl mb-8 bg-black flex items-center justify-center overflow-hidden group">
            
            <div id="reader" class="w-full h-full object-cover"></div>
            
            <div class="laser-line absolute top-0 left-0 right-0 h-1 bg-green-500 shadow-[0_0_15px_#22c55e] z-20 pointer-events-none"></div>
            
            <i id="cam-icon" class="fa-solid fa-qrcode text-7xl text-gray-600 absolute z-10"></i>
            
            <div class="absolute top-4 left-4 w-6 h-6 border-t-4 border-l-4 border-green-500 rounded-tl-lg z-20 pointer-events-none"></div>
            <div class="absolute top-4 right-4 w-6 h-6 border-t-4 border-r-4 border-green-500 rounded-tr-lg z-20 pointer-events-none"></div>
            <div class="absolute bottom-4 left-4 w-6 h-6 border-b-4 border-l-4 border-green-500 rounded-bl-lg z-20 pointer-events-none"></div>
            <div class="absolute bottom-4 right-4 w-6 h-6 border-b-4 border-r-4 border-green-500 rounded-br-lg z-20 pointer-events-none"></div>
        </div>

        <div class="space-y-4 relative z-10">
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                <span class="h-px w-16 bg-gray-200"></span>
                <span>HOẶC NHẬP MÃ THỦ CÔNG</span>
                <span class="h-px w-16 bg-gray-200"></span>
            </div>
            
            <form id="manual-form" class="flex space-x-2" onsubmit="event.preventDefault(); handleManualSubmit();">
                <input type="text" id="manual-input" placeholder="VD: LOT-2026-..." class="flex-1 bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition font-mono">
                <button type="submit" class="bg-[#315787] hover:bg-[#254268] text-white px-6 rounded-xl font-medium transition shadow-sm">
                    Tra cứu
                </button>
            </form>
        </div>
    </div>
</main>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    // 1. Hàm chuyển hướng sang trang chủ để bắt đầu tìm kiếm
    function processQRCode(decodedText) {
        // Lưu mã tìm được vào bộ nhớ tạm (giống tính năng ở trang danh-sach.php)
        localStorage.setItem('searchId', decodedText);
        
        // Thêm SweetAlert2 báo thành công nhẹ nhàng rồi chuyển trang
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Đã nhận diện mã: ' + decodedText,
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = 'index.php'; // Trở về trang chủ
        });
    }

    // 2. Xử lý quét bằng Camera thành công
    function onScanSuccess(decodedText, decodedResult) {
        // Dừng máy quét để tránh quét liên tục nhiều lần
        html5QrCode.stop().then((ignore) => {
            processQRCode(decodedText);
        }).catch((err) => {
            console.error("Lỗi khi dừng camera", err);
        });
    }

    // 3. Khởi tạo và Bật Camera
    const html5QrCode = new Html5Qrcode("reader");
    const config = { fps: 10, qrbox: { width: 200, height: 200 } };

    // Tự động yêu cầu quyền mở Camera khi load trang
    html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
    .then(() => {
        // Nếu mở cam thành công thì ẩn cái icon QR ở giữa đi
        document.getElementById('cam-icon').style.display = 'none';
    })
    .catch((err) => {
        console.warn("Không thể mở Camera:", err);
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'Vui lòng cấp quyền sử dụng Camera / Webcam để quét mã!',
            showConfirmButton: false,
            timer: 4000
        });
    });

    // 4. Xử lý khi người dùng lười quét, tự nhập bằng tay
    function handleManualSubmit() {
        const val = document.getElementById('manual-input').value.trim();
        if(val) {
            processQRCode(val);
        } else {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Vui lòng nhập mã lô hàng!',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>

<?php include 'footer.php'; ?>