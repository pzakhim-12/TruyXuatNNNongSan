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
<script>
    // -------------------------------------------------------------------------
    // CẤU HÌNH SMART CONTRACT
    // -------------------------------------------------------------------------
    const contractAddress = "0x82bd170ad2a3ACb79Fe7262dDb710d823A731577"; 
    const contractABI = [
        {"anonymous":false,"inputs":[{"indexed":false,"internalType":"string","name":"id","type":"string"},{"indexed":false,"internalType":"string","name":"name","type":"string"},{"indexed":true,"internalType":"address","name":"farmer","type":"address"}],"name":"ProductCreated","type":"event"},
        {"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"userRoles","outputs":[{"internalType":"enum AgriChain.Role","name":"","type":"uint8"}],"stateMutability":"view","type":"function"}
    ];

    let provider;
    let agrichainContract;

    // 1. Hàm tự động kết nối ví để giữ màu cho Navbar (giống bên index.php)
    async function initWallet() {
        if (typeof window.ethereum !== 'undefined') {
            try {
                provider = new ethers.providers.Web3Provider(window.ethereum);
                const accounts = await provider.listAccounts();
                if (accounts.length > 0) {
                    const signer = provider.getSigner();
                    const userAddress = await signer.getAddress();
                    const contract = new ethers.Contract(contractAddress, contractABI, signer);
                    const roleCode = await contract.userRoles(userAddress);
                    
                    let roleName = "Chưa phân quyền";
                    let roleColor = "bg-gray-600"; 
                    if (roleCode == 1) { roleName = "Admin"; roleColor = "bg-red-600"; }
                    else if (roleCode == 2) { roleName = "Nông dân"; roleColor = "bg-green-600"; }
                    else if (roleCode == 3) { roleName = "Kiểm định"; roleColor = "bg-blue-600"; }
                    else if (roleCode == 4) { roleName = "Vận chuyển"; roleColor = "bg-amber-600"; }

                    const shortAddress = userAddress.substring(0, 5) + "..." + userAddress.substring(38);
                    const btnConnect = document.getElementById("btnConnect");
                    const addressDisplay = document.getElementById("walletAddressDisplay");
                    if(btnConnect && addressDisplay) {
                        btnConnect.className = `${roleColor} hover:opacity-80 text-white font-semibold px-4 py-2 rounded-xl text-sm shadow-md transition flex items-center space-x-2`;
                        addressDisplay.innerText = `${shortAddress} (${roleName})`;
                    }
                }
            } catch (error) { console.error("Lỗi:", error); }
        }
    }

    // 2. Hàm Quét lịch sử và vẽ lên cái bảng siêu đẹp của Sếp
    async function loadProducts() {
        if (typeof window.ethereum === 'undefined') {
            document.getElementById('table-body').innerHTML = `<tr><td colspan="5" class="p-8 text-center text-red-500 font-semibold">🦊 Vui lòng cài đặt MetaMask để xem dữ liệu!</td></tr>`;
            return;
        }

        try {
            provider = new ethers.providers.Web3Provider(window.ethereum);
            agrichainContract = new ethers.Contract(contractAddress, contractABI, provider);

            // Xử lý né lỗi 10000 blocks của RPC
            const currentBlock = await provider.getBlockNumber();
            const startBlock = currentBlock - 9000;
            
            const filter = agrichainContract.filters.ProductCreated();
            const events = await agrichainContract.queryFilter(filter, startBlock > 0 ? startBlock : 0, "latest");

            const tbody = document.getElementById('table-body');
            
            if(events.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-10 text-center text-gray-500">Chưa có lô hàng nào trên hệ thống. Trống trơn!</td></tr>`;
                document.getElementById('total-results').innerText = `Hiển thị 0 kết quả`;
                return;
            }

            let rowsHTML = "";
            
            // Lặp ngược mảng để những lô hàng mới tạo sẽ ngoi lên đầu bảng
            [...events].reverse().forEach((event) => {
                const id = event.args.id;
                const name = event.args.name;
                const farmerWallet = event.args.farmer;
                // Rút gọn ví cho đẹp bảng (VD: 0x82b...1577)
                const shortWallet = farmerWallet.substring(0, 5) + '...' + farmerWallet.substring(38);

                rowsHTML += `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-mono font-medium text-blue-600">${id}</td>
                        <td class="p-4 font-medium text-gray-900">${name}</td>
                        <td class="p-4">
                            <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg border border-gray-200 font-mono text-xs" title="${farmerWallet}">
                                <i class="fa-solid fa-wallet mr-1 text-gray-400"></i>${shortWallet}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <i class="fa-solid fa-circle-check text-green-500 text-lg" title="Đã đồng bộ lên Blockchain"></i>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <button onclick="copyAndGo('${id}')" class="text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 p-2 rounded-lg transition" title="Tra cứu lô hàng này">
                                <i class="fa-solid fa-qrcode"></i> Quét
                            </button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = rowsHTML;
            document.getElementById('total-results').innerText = `Đã hiển thị ${events.length} lô hàng từ Blockchain`;

        } catch (error) {
            console.error("Lỗi:", error);
            document.getElementById('table-body').innerHTML = `<tr><td colspan="5" class="p-8 text-center text-red-500 font-semibold">Lỗi kết nối mạng lưới. Vui lòng F5 lại trang!</td></tr>`;
        }
    }

    // 3. Hàm hỗ trợ bấm nút "Quét" -> Tự copy mã và nhảy sang trang chủ
    function copyAndGo(id) {
        // Lưu tạm id vào bộ nhớ trình duyệt để sang trang index dùng
        localStorage.setItem('searchId', id);
        window.location.href = 'index.php';
    }

    // Chạy các hàm khi tải trang xong
    document.addEventListener("DOMContentLoaded", () => {
        initWallet();
        loadProducts();
    });
</script>

<?php include 'footer.php'; ?>