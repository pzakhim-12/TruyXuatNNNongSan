<?php include 'header.php'; ?>

<main class="flex-1 max-w-4xl w-full mx-auto p-6 space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Quản trị Phân quyền (RBAC)</h2>
        <p class="text-sm text-gray-500 mt-1">Bảng điều khiển dành riêng cho Giám đốc/Admin hệ thống.</p>
    </div>

    <div id="access-denied" class="hidden bg-white rounded-2xl border border-gray-200 shadow-sm p-16 text-center">
        <div class="relative inline-block mb-6">
            <i class="fa-solid fa-shield-halved text-7xl text-gray-200"></i>
            <i class="fa-solid fa-lock text-3xl text-red-500 absolute bottom-0 right-0"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Truy cập bị từ chối!</h3>
        <p class="text-gray-500">Ví hiện tại của bạn không có thẩm quyền truy cập khu vực này.</p>
        <p class="text-sm text-gray-400 mt-1">Vui lòng chuyển đổi sang tài khoản Admin trên MetaMask để tiếp tục.</p>
        <a href="index.php" class="inline-block mt-6 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Quay lại trang chủ
        </a>
    </div>

    <div id="admin-workspace" class="space-y-6 hidden">
        
        <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl shadow-sm">
                Khu vực Đặc quyền
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <i class="fa-solid fa-user-shield text-red-600 mr-2"></i>Cấp quyền cho Ví nhân viên
            </h3>
            
            <form class="space-y-5" onsubmit="event.preventDefault();">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Địa chỉ ví MetaMask cần cấp</label>
                    <input type="text" id="target-address" placeholder="Ví dụ: 0x123...abc" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Lựa chọn Vai trò (Role)</label>
                    <select id="target-role" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                        <option value="2">🟢 Nông dân (Được phép tạo lô hàng)</option>
                        <option value="3">🔵 Kiểm định viên (Được phép cập nhật trạng thái QC)</option>
                        <option value="4">🟠 Đơn vị Vận chuyển (Được phép cập nhật kho bãi, vận tải)</option>
                        <option value="1">🔴 Quản trị viên (Admin - Full quyền)</option>
                        <option value="0">⚪ Hủy quyền (Biến thành ví thường)</option>
                    </select>
                </div>
                <button id="btn-assign" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3.5 rounded-xl text-sm transition mt-2 shadow-md">
                    <i class="fa-solid fa-stamp mr-2"></i>Đóng dấu & Ký xác nhận lên Blockchain
                </button>
            </form>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <h3 class="text-md font-bold text-gray-900 mb-3"><i class="fa-solid fa-magnifying-glass mr-2 text-gray-400"></i>Tra cứu quyền hiện tại của một ví</h3>
            <div class="flex space-x-2">
                <input type="text" id="check-address" placeholder="Nhập địa chỉ ví cần kiểm tra..." class="flex-1 bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-gray-500">
                <button id="btn-check" class="bg-gray-800 hover:bg-gray-900 text-white px-5 rounded-xl text-sm font-medium transition shadow-sm">Kiểm tra</button>
            </div>
        </div>

    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.min.js"></script>
<script src="js/config.js"></script>
<script src="js/utils.js"></script>
<script src="js/admin.js"></script>

<?php include 'footer.php'; ?>