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
<script>
    // -------------------------------------------------------------------------
    // CẤU HÌNH SMART CONTRACT
    // -------------------------------------------------------------------------
    const contractAddress = "0x82bd170ad2a3ACb79Fe7262dDb710d823A731577"; 
    const contractABI = [
        {"inputs":[{"internalType":"address","name":"_user","type":"address"},{"internalType":"enum AgriChain.Role","name":"_role","type":"uint8"}],"name":"assignRole","outputs":[],"stateMutability":"nonpayable","type":"function"},
        {"inputs":[{"internalType":"string","name":"_id","type":"string"},{"internalType":"string","name":"_name","type":"string"},{"internalType":"uint256","name":"_weight","type":"uint256"},{"internalType":"string","name":"_location","type":"string"}],"name":"createProduct","outputs":[],"stateMutability":"nonpayable","type":"function"},
        {"inputs":[],"stateMutability":"nonpayable","type":"constructor"},
        {"anonymous":false,"inputs":[{"indexed":false,"internalType":"string","name":"id","type":"string"},{"indexed":false,"internalType":"string","name":"name","type":"string"},{"indexed":true,"internalType":"address","name":"farmer","type":"address"}],"name":"ProductCreated","type":"event"},
        {"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"user","type":"address"},{"indexed":false,"internalType":"enum AgriChain.Role","name":"role","type":"uint8"}],"name":"RoleAssigned","type":"event"},
        {"anonymous":false,"inputs":[{"indexed":false,"internalType":"string","name":"id","type":"string"},{"indexed":false,"internalType":"string","name":"action","type":"string"},{"indexed":true,"internalType":"address","name":"handler","type":"address"}],"name":"StatusUpdated","type":"event"},
        {"inputs":[{"internalType":"string","name":"_id","type":"string"},{"internalType":"string","name":"_action","type":"string"},{"internalType":"string","name":"_location","type":"string"}],"name":"updateStatus","outputs":[],"stateMutability":"nonpayable","type":"function"},
        {"inputs":[],"name":"getMyRole","outputs":[{"internalType":"enum AgriChain.Role","name":"","type":"uint8"}],"stateMutability":"view","type":"function"},
        {"inputs":[{"internalType":"string","name":"_id","type":"string"}],"name":"getProductHistory","outputs":[{"components":[{"internalType":"address","name":"handler","type":"address"},{"internalType":"string","name":"action","type":"string"},{"internalType":"string","name":"location","type":"string"},{"internalType":"uint256","name":"timestamp","type":"uint256"}],"internalType":"struct AgriChain.Traceability[]","name":"","type":"tuple[]"}],"stateMutability":"view","type":"function"},
        {"inputs":[{"internalType":"string","name":"","type":"string"},{"internalType":"uint256","name":"","type":"uint256"}],"name":"productHistory","outputs":[{"internalType":"address","name":"handler","type":"address"},{"internalType":"string","name":"action","type":"string"},{"internalType":"string","name":"location","type":"string"},{"internalType":"uint256","name":"timestamp","type":"uint256"}],"stateMutability":"view","type":"function"},
        {"inputs":[{"internalType":"string","name":"","type":"string"}],"name":"products","outputs":[{"internalType":"string","name":"id","type":"string"},{"internalType":"string","name":"name","type":"string"},{"internalType":"uint256","name":"weight","type":"uint256"},{"internalType":"bool","name":"isInitialized","type":"bool"}],"stateMutability":"view","type":"function"},
        {"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"userRoles","outputs":[{"internalType":"enum AgriChain.Role","name":"","type":"uint8"}],"stateMutability":"view","type":"function"}
    ];

    let provider;
    let signer;
    let agrichainContract;
    let userAddress = "";

    // -------------------------------------------------------------------------
    // HÀM DÙNG CHUNG: Quét event theo từng đợt (chunk) để không bỏ sót lô hàng cũ
    // Nhiều RPC free-tier chỉ cho quét tối đa ~9000-10000 block/lần, nên thay vì
    // chỉ nhìn 9000 block gần nhất (làm lô hàng cũ "biến mất" theo thời gian),
    // ta lùi dần từng đợt 9000 block cho tới khi hết lịch sử hoặc chạm maxChunks.
    // -------------------------------------------------------------------------
    async function queryEventsInChunks(contract, filter, currentProvider, chunkSize = 9000, maxChunks = 50) {
        const currentBlock = await currentProvider.getBlockNumber();
        let toBlock = currentBlock;
        let allEvents = [];
        let chunksQueried = 0;

        while (toBlock >= 0 && chunksQueried < maxChunks) {
            const fromBlock = Math.max(toBlock - chunkSize + 1, 0);
            try {
                const events = await contract.queryFilter(filter, fromBlock, toBlock);
                allEvents = allEvents.concat(events);
            } catch (err) {
                console.warn(`Lỗi khi quét block ${fromBlock}-${toBlock}, dừng quét thêm:`, err);
                break;
            }
            if (fromBlock === 0) break;
            toBlock = fromBlock - 1;
            chunksQueried++;
        }

        // Sắp xếp lại theo thứ tự thời gian tăng dần (giống thứ tự queryFilter gốc)
        allEvents.sort((a, b) => a.blockNumber - b.blockNumber || a.logIndex - b.logIndex);
        return allEvents;
    }

    // -------------------------------------------------------------------------
    // HÀM KIỂM TRA IM LẶNG: Tự động nhận diện ví và theo dõi MetaMask
    // -------------------------------------------------------------------------
    async function initWallet() {
        if (typeof window.ethereum !== 'undefined') {
            try {
                provider = new ethers.providers.Web3Provider(window.ethereum);
                agrichainContract = new ethers.Contract(contractAddress, contractABI, provider);
                
                // Lắng nghe sự kiện đổi ví
                if (window.ethereum) {
                    window.ethereum.on('accountsChanged', function (accounts) {
                        console.log("Đã phát hiện đổi ví:", accounts[0]);
                        window.location.reload(); // Ép trình duyệt F5 tải lại trang
                    });
                    
                    window.ethereum.on('chainChanged', function (chainId) {
                        window.location.reload(); 
                    });
                }
                
                // 1. Tự động đếm lô hàng
                loadDashboardStats();

                // 2. Kiểm tra ví đã kết nối web chưa
                const accounts = await provider.listAccounts();
                if (accounts.length > 0) {
                    signer = provider.getSigner();
                    agrichainContract = new ethers.Contract(contractAddress, contractABI, signer);
                    userAddress = await signer.getAddress();
                    
                    const roleCode = await agrichainContract.userRoles(userAddress);
                    
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
            } catch (error) {
                console.error("Lỗi tự động nhận diện ví:", error);
            }
        }
    }

    // -------------------------------------------------------------------------
    // HÀM 1: KẾT NỐI VÍ THỦ CÔNG
    // -------------------------------------------------------------------------
    async function connectWallet() {
        if (typeof window.ethereum !== 'undefined') {
            try {
                provider = new ethers.providers.Web3Provider(window.ethereum);
                await provider.send("eth_requestAccounts", []); 
                signer = provider.getSigner();
                agrichainContract = new ethers.Contract(contractAddress, contractABI, signer);
                userAddress = await signer.getAddress();
                
                const roleCode = await agrichainContract.userRoles(userAddress);
                
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

                loadDashboardStats();
                return true;
            } catch (error) {
                console.error("Chi tiết lỗi ví:", error);
                Swal.fire({ icon: 'error', title: 'Lỗi kết nối', text: 'Người dùng từ chối hoặc có lỗi từ ví!' });
                return false;
            }
        } else {
            Swal.fire({ icon: 'warning', title: 'Thiếu tiện ích', text: 'Vui lòng cài đặt MetaMask để sử dụng Web3!' });
            return false;
        }
    }

    // -------------------------------------------------------------------------
    // HÀM 2: QUÉT BLOCKCHAIN TỰ ĐỘNG ĐẾM
    // -------------------------------------------------------------------------
    async function loadDashboardStats() {
        try {
            const filter = agrichainContract.filters.ProductCreated();
            const events = await queryEventsInChunks(agrichainContract, filter, provider);
            
            const totalBatchesElement = document.getElementById('total-batches');
            if(totalBatchesElement) {
                totalBatchesElement.innerText = events.length + " lô";
            }

            if(events.length > 0) {
                const latestBatchId = events[events.length - 1].args.id;
                const searchInput = document.getElementById('search-input');
                if(searchInput) {
                    searchInput.value = latestBatchId;
                }
            }
        } catch (error) {
            console.error("Lỗi khi đọc lịch sử mạng lưới:", error);
            document.getElementById('total-batches').innerText = "0 lô"; 
        }
    }

    // -------------------------------------------------------------------------
    // HÀM 3: KHỞI TẠO LÔ HÀNG (FARMER)
    // -------------------------------------------------------------------------
    async function handleCreateProduct() {
        const isConnected = await connectWallet();
        if (!isConnected) return;

        const productName = document.getElementById('product-name-input').value.trim();
        const regionCode = document.getElementById('product-region-input').value.trim();
        const rawLocation = document.getElementById('location-input').value.trim();
        const weightRaw = document.getElementById('product-weight-input').value;

        if (!productName || !rawLocation) {
            Swal.fire({ icon: 'warning', title: 'Thiếu thông tin', text: 'Vui lòng nhập Tên sản phẩm và Vị trí!' });
            return;
        }

        const weight = parseInt(weightRaw, 10);
        if (!Number.isInteger(weight) || weight <= 0) {
            Swal.fire({ icon: 'warning', title: 'Khối lượng không hợp lệ', text: 'Khối lượng (Tấn) phải là số nguyên dương!' });
            return;
        }

        // Gộp mã vùng trồng vào vị trí vì contract chỉ nhận 4 tham số (không có ô riêng cho vùng trồng)
        const location = regionCode ? `${rawLocation} (Vùng trồng: ${regionCode})` : rawLocation;

        const productId = "LOT-" + new Date().getTime(); 

        try {
            const btn = document.getElementById('btn-create');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Đang chờ ví xác nhận...';
            btn.disabled = true;

            const tx = await agrichainContract.createProduct(productId, productName, weight, location);
            btn.innerHTML = '<i class="fa-solid fa-link fa-spin mr-2"></i>Đang ghi lên mạng...';
            
            await tx.wait(); 
            
            Swal.fire({
                icon: 'success',
                title: 'Khởi tạo thành công!',
                text: `Mã lô hàng mới: ${productId}`,
                confirmButtonColor: '#16a34a'
            });
            
            btn.innerHTML = originalText;
            btn.disabled = false;
            loadDashboardStats();
            
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Giao dịch thất bại',
                text: 'Giao dịch bị từ chối! Hãy chắc chắn ví của bạn có quyền Nông dân.',
                confirmButtonColor: '#d33'
            });
            const btn = document.getElementById('btn-create');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-arrow-up-from-bracket mr-2"></i>Ký tên & Đẩy lên Blockchain';
        }
    }

    // -------------------------------------------------------------------------
    // HÀM 4: QUÉT MÃ QR (TRUY XUẤT NGUỒN GỐC)
    // -------------------------------------------------------------------------
    async function handleSearchProduct() {
        const searchInput = document.getElementById('search-input').value.trim();
        
        if(!searchInput) {
            Swal.fire({ icon: 'info', title: 'Chú ý', text: 'Vui lòng nhập mã lô hàng!' });
            return;
        }

        try {
            const searchBtn = document.getElementById('btn-search');
            const originalText = searchBtn.innerHTML;
            searchBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            
            const readProvider = new ethers.providers.Web3Provider(window.ethereum);
            const contractRead = new ethers.Contract(contractAddress, contractABI, readProvider);

            const history = await contractRead.getProductHistory(searchInput);
            
            if(history.length === 0) {
                Swal.fire({ icon: 'error', title: 'Không tìm thấy', text: 'Mã lô hàng không tồn tại trên mạng lưới Blockchain!' });
                searchBtn.innerHTML = originalText;
                return;
            }

            // Vẽ Header Timeline
            const headerContainer = document.getElementById('timeline-header');
            headerContainer.innerHTML = `
                <div>
                    <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold border border-green-200"><i class="fa-solid fa-check mr-1"></i>Dữ liệu hợp lệ từ Blockchain</span>
                    <h2 class="text-xl font-bold text-gray-900 mt-2">Lô hàng: ${searchInput}</h2>
                    <p class="text-xs text-gray-500 mt-1">Dữ liệu được bảo vệ và mã hóa bởi mạng Ethereum</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-500 block">Số giai đoạn ghi nhận</span>
                    <span class="text-lg font-bold text-blue-600 block mt-1">${history.length} Giai đoạn</span>
                </div>
            `;

            // Vẽ Body Timeline
            const timelineContainer = document.getElementById('timeline-body');
            let timelineHTML = '';
            
            const styles = [
                { color: 'green', icon: 'fa-tractor' },
                { color: 'blue', icon: 'fa-clipboard-check' },
                { color: 'amber', icon: 'fa-truck' },
                { color: 'purple', icon: 'fa-store' }
            ];

            history.forEach((stage, index) => {
                let date = new Date(stage.timestamp * 1000).toLocaleString('vi-VN');
                let shortWallet = stage.handler.substring(0, 5) + '...' + stage.handler.substring(38);
                let style = styles[index % styles.length];

                timelineHTML += `
                <div class="relative">
                    <div class="absolute -left-6 top-1.5 w-4 h-4 rounded-full bg-${style.color}-500 border-4 border-white ring-4 ring-${style.color}-50"></div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-gray-900 text-sm"><i class="fa-solid ${style.icon} text-${style.color}-600 mr-1.5"></i>Giai đoạn ${index + 1}: ${stage.action}</h4>
                            <span class="text-[11px] text-gray-500">${date}</span>
                        </div>
                        <p class="text-xs text-gray-600">Vị trí ghi nhận: <strong>${stage.location}</strong></p>
                        <div class="pt-2 border-t border-gray-200 flex justify-between items-center text-[11px] font-mono">
                            <span class="text-gray-500">Ví thực hiện: <span class="text-gray-800 font-semibold">${shortWallet}</span></span>
                            <span class="text-${style.color}-700 bg-${style.color}-100 px-2 py-0.5 rounded border border-${style.color}-200"><i class="fa-solid fa-link mr-1"></i>Đã xác thực</span>
                        </div>
                    </div>
                </div>
                `;
            });

            timelineContainer.innerHTML = timelineHTML;
            searchBtn.innerHTML = originalText;
            
        } catch (error) {
            console.error("Lỗi truy xuất:", error);
            Swal.fire({ icon: 'error', title: 'Lỗi truy xuất', text: 'Đã xảy ra lỗi khi đọc mạng lưới!' });
            document.getElementById('btn-search').innerHTML = 'Tìm';
        }
    }

    // -------------------------------------------------------------------------
    // HÀM 5: CẬP NHẬT TRẠNG THÁI (INSPECTOR, LOGISTICS)
    // -------------------------------------------------------------------------
    async function handleUpdateStatus() {
        const id = document.getElementById('update-id').value.trim();
        const action = document.getElementById('update-action').value;
        const location = document.getElementById('update-location').value.trim();

        if(!id || !location) {
            Swal.fire({ icon: 'warning', title: 'Thiếu thông tin', text: 'Vui lòng điền đầy đủ Mã lô hàng và Vị trí!' });
            return;
        }

        try {
            const isConnected = await connectWallet();
            if (!isConnected) return;

            const btn = document.querySelector('button[onclick="handleUpdateStatus()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Đang ghi...';
            
            const tx = await agrichainContract.updateStatus(id, action, location);
            await tx.wait(); 

            Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Đã cập nhật giai đoạn lên Blockchain.', confirmButtonColor: '#f59e0b' });
            btn.innerHTML = originalText;
            
            // Tự động load lại biểu đồ tra cứu
            document.getElementById('search-input').value = id;
            document.getElementById('btn-search').click();

        } catch (error) {
            console.error("Lỗi:", error);
            Swal.fire({ icon: 'error', title: 'Thất bại', text: 'Giao dịch bị từ chối! Đảm bảo mã hợp lệ hoặc ví có đúng Quyền (Role).' });
            document.querySelector('button[onclick="handleUpdateStatus()"]').innerHTML = 'Cập nhật lên Blockchain';
        }
    }

    // -------------------------------------------------------------------------
    // GẮN SỰ KIỆN KHI TRANG LOAD XONG
    // -------------------------------------------------------------------------
    document.addEventListener("DOMContentLoaded", () => {
        initWallet();
        
        // 1. Nút Tạo lô hàng
        const btnCreate = document.getElementById('btn-create');
        if(btnCreate) {
            btnCreate.addEventListener('click', handleCreateProduct);
        }

        // 2. Nút Tìm kiếm
        const btnSearch = document.getElementById('btn-search');
        if(btnSearch) {
            btnSearch.addEventListener('click', handleSearchProduct);
        }
        
        // 3. Xử lý quét mã tự động từ trang "Danh sách"
        const savedId = localStorage.getItem('searchId');
        if (savedId) {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.value = savedId;
                localStorage.removeItem('searchId');
                
                // Đợi load giao diện rồi tự quét
                setTimeout(() => {
                    const searchBtn = document.getElementById('btn-search');
                    if (searchBtn) searchBtn.click();
                }, 500);
            }
        }
    });

    function getGPSLocation() {
        const locationInput = document.getElementById('location-input');
        
        // Đổi chữ trong ô để báo đang tải
        locationInput.value = "Đang định vị...";

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Lấy thành công Tọa độ (Vĩ độ, Kinh độ)
                    const lat = position.coords.latitude.toFixed(5);
                    const lng = position.coords.longitude.toFixed(5);
                    
                    // Ghi vào ô input
                    locationInput.value = lat + ", " + lng;
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Đã lấy tọa độ GPS thành công!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }, 
                function(error) {
                    // Xử lý lỗi (Người dùng từ chối cấp quyền, hoặc máy không có GPS)
                    locationInput.value = "";
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi định vị',
                        text: 'Vui lòng cho phép trình duyệt truy cập Vị trí (Location) của bạn!'
                    });
                }
            );
        } else {
            alert("Trình duyệt của bạn không hỗ trợ định vị GPS.");
        }
    }
</script>

<?php include 'footer.php'; ?> 