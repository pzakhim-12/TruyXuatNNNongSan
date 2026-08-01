<?php include 'header.php'; ?>

<main class="flex-1 max-w-7xl w-full mx-auto p-6 space-y-8">
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i class="fa-solid fa-box text-2xl"></i></div>
            <div>
                <span class="text-xs text-gray-500 block font-medium">Tổng lô hàng tạo</span>
                <span class="text-2xl font-bold text-gray-900" id="total-batches">Đang tải...</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl"><i class="fa-solid fa-shield-blockchain text-2xl"></i></div>
            <div>
                <span class="text-xs text-gray-500 block font-medium">Đã ghi lên mạng</span>
                <span class="text-2xl font-bold text-gray-900">100% Khớp</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-amber-50 text-amber-500 rounded-xl"><i class="fa-solid fa-truck-fast text-2xl"></i></div>
            <div>
                <span class="text-xs text-gray-500 block font-medium">Đang vận chuyển</span>
                <span class="text-2xl font-bold text-gray-900">-- lô</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl"><i class="fa-solid fa-building-columns text-2xl"></i></div>
            <div>
                <span class="text-xs text-gray-500 block font-medium">Địa chỉ Smart Contract</span>
                <span class="text-sm font-mono text-gray-600 block truncate w-36">0x82b...1577</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-circle-plus text-green-600 mr-2"></i>Khởi tạo lô hàng mới
                </h3>
                <form class="space-y-4" id="form-create" onsubmit="event.preventDefault();">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Tên sản phẩm nông sản</label>
                        <input type="text" id="product-name-input" value="Thanh Long Ruột Đỏ Bình Thuận" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Khối lượng (Tấn)</label>
                            <input type="number" id="product-weight-input" value="5" min="1" step="1" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Mã vùng trồng</label>
                            <input type="text" id="product-region-input" value="VN-BTH-082" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="location-input" placeholder="Nhập vị trí hoặc tọa độ..." class="flex-1 bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500">
                        
                        <button type="button" onclick="getGPSLocation()" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-4 rounded-xl font-medium transition" title="Tự động lấy tọa độ GPS">
                            <i class="fa-solid fa-location-dot"></i> GPS
                        </button>
                    </div>
                    <button id="btn-create" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl text-sm transition mt-2 shadow-md">
                        <i class="fa-solid fa-arrow-up-from-bracket mr-2"></i>Ký tên & Đẩy lên Blockchain
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm text-center">
                <i class="fa-solid fa-qrcode text-5xl text-[#315787] mb-4"></i>
                <h4 class="text-gray-900 font-bold mb-1">Mô phỏng quét mã QR</h4>
                <p class="text-xs text-gray-500 mb-4">Nhập mã lô hàng dưới đây để kiểm tra dòng lịch sử bất biến.</p>
                <div class="flex space-x-2">
                    <input type="text" id="search-input" placeholder="Nhập LOT-..." class="flex-1 bg-gray-50 border border-gray-300 rounded-xl px-4 py-2 text-sm text-gray-900 font-mono text-center focus:outline-none focus:border-[#315787]">
                    <button id="btn-search" class="bg-[#315787] hover:bg-[#254268] text-white px-4 rounded-xl text-sm font-medium transition">Tìm</button>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm text-center mt-6">
                <i class="fa-solid fa-truck-fast text-4xl text-amber-500 mb-4"></i>
                <h4 class="text-gray-900 font-bold mb-1">Cập nhật chuỗi cung ứng</h4>
                <p class="text-xs text-gray-500 mb-4">Ghi nhận các giai đoạn tiếp theo lên mạng lưới.</p>
                <div class="space-y-3">
                    <input type="text" id="update-id" placeholder="Mã lô hàng (VD: LOT-...)" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-amber-500">
                    <select id="update-action" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-amber-500">
                        <option value="Kiểm định & Đóng gói chuẩn VietGAP">📋 Kiểm định & Đóng gói</option>
                        <option value="Vận chuyển xe lạnh 5°C">🚚 Vận chuyển xe lạnh</option>
                        <option value="Nhập kho siêu thị bán lẻ">🏪 Nhập kho siêu thị</option>
                    </select>
                    <input type="text" id="update-location" placeholder="Vị trí (VD: Kho lạnh TP.HCM)" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-amber-500">
                    <button onclick="handleUpdateStatus()" class="w-full bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                        Cập nhật lên Blockchain
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-6">
            
            <div id="timeline-header" class="flex justify-between items-start border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-400 mt-2">Chưa có dữ liệu tra cứu</h2>
                    <p class="text-xs text-gray-500 mt-1">Vui lòng nhập mã lô hàng để xem lịch sử truy xuất.</p>
                </div>
            </div>

            <div id="timeline-body" class="relative pl-6 space-y-8 before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                </div>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.min.js"></script>
<script src="js/config.js"></script>
<script src="js/utils.js"></script>
<script src="js/index.js"></script>

<?php include 'footer.php'; ?>