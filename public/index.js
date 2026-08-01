// index.js — Logic riêng cho trang index.php (Dashboard, Tạo lô hàng, Tra cứu, Cập nhật)
// Cần load SAU config.js và utils.js.

// -------------------------------------------------------------------------
// HÀM KIỂM TRA IM LẶNG: Tự động nhận diện ví và theo dõi MetaMask
// -------------------------------------------------------------------------
async function initWallet() {
    if (typeof window.ethereum !== 'undefined') {
        try {
            provider = new ethers.providers.Web3Provider(window.ethereum);
            agrichainContract = new ethers.Contract(contractAddress, contractABI, provider);

            if (window.ethereum) {
                window.ethereum.on('accountsChanged', function (accounts) {
                    console.log("Đã phát hiện đổi ví:", accounts[0]);
                    window.location.reload();
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
                updateWalletNavbar(userAddress, roleCode);
            }
        } catch (error) {
            console.error("Lỗi tự động nhận diện ví:", error);
        }
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
        if (totalBatchesElement) {
            totalBatchesElement.innerText = events.length + " lô";
        }

        if (events.length > 0) {
            const latestBatchId = events[events.length - 1].args.id;
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
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

    if (!searchInput) {
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

        if (history.length === 0) {
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
            let shortWallet = formatShortAddress(stage.handler);
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

    if (!id || !location) {
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
// LẤY GPS (không liên quan blockchain — thuần browser API)
// -------------------------------------------------------------------------
function getGPSLocation() {
    const locationInput = document.getElementById('location-input');

    locationInput.value = "Đang định vị...";

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude.toFixed(5);
                const lng = position.coords.longitude.toFixed(5);

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
            function (error) {
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

// -------------------------------------------------------------------------
// GẮN SỰ KIỆN KHI TRANG LOAD XONG
// -------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    initWallet();

    const btnCreate = document.getElementById('btn-create');
    if (btnCreate) {
        btnCreate.addEventListener('click', handleCreateProduct);
    }

    const btnSearch = document.getElementById('btn-search');
    if (btnSearch) {
        btnSearch.addEventListener('click', handleSearchProduct);
    }

    // Xử lý quét mã tự động từ trang "Danh sách" / "Quét mã"
    const savedId = localStorage.getItem('searchId');
    if (savedId) {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.value = savedId;
            localStorage.removeItem('searchId');

            setTimeout(() => {
                const searchBtn = document.getElementById('btn-search');
                if (searchBtn) searchBtn.click();
            }, 500);
        }
    }
});
