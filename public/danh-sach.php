<?php include 'header.php'; ?>

<main class="flex-1 max-w-7xl w-full mx-auto p-6 space-y-6">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Quản lý lô hàng</h2>
            <p class="text-sm text-gray-500 mt-1">Danh sách toàn bộ nông sản đã được khởi tạo và ghi nhận.</p>
        </div>
        <a href="index.php" class="bg-[#315787] hover:bg-[#254268] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Tạo lô mới
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="p-4 font-semibold">Mã Lô Hàng</th>
                        <th class="p-4 font-semibold">Tên Sản Phẩm</th>
                        <th class="p-4 font-semibold">Người Tạo (Ví Nông Dân)</th>
                        <th class="p-4 font-semibold text-center">Trạng Thái Blockchain</th>
                        <th class="p-4 font-semibold text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-100">
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-500">
                            <i class="fa-solid fa-spinner fa-spin text-2xl mb-3 text-green-500 block"></i>
                            Đang đồng bộ dữ liệu từ mạng lưới Sepolia...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 border-t border-gray-200 p-4 flex justify-between items-center text-sm text-gray-500">
            <span id="total-results">Đang tính toán kết quả...</span>
            <div class="space-x-1 opacity-50 cursor-not-allowed" title="Tính năng phân trang đang cập nhật">
                <button class="px-3 py-1 bg-white border border-gray-300 rounded" disabled>Trước</button>
                <button class="px-3 py-1 bg-green-600 text-white rounded" disabled>1</button>
                <button class="px-3 py-1 bg-white border border-gray-300 rounded" disabled>Sau</button>
            </div>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.min.js"></script>
<script src="js/config.js"></script>
<script src="js/utils.js"></script>
<script src="js/danh-sach.js"></script>

<?php include 'footer.php'; ?>