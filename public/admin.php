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
<script>
    const contractAddress = "0x82bd170ad2a3ACb79Fe7262dDb710d823A731577"; 
    const contractABI = [
        {"inputs":[{"internalType":"address","name":"_user","type":"address"},{"internalType":"enum AgriChain.Role","name":"_role","type":"uint8"}],"name":"assignRole","outputs":[],"stateMutability":"nonpayable","type":"function"},
        {"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"userRoles","outputs":[{"internalType":"enum AgriChain.Role","name":"","type":"uint8"}],"stateMutability":"view","type":"function"}
    ];

    let provider;
    let signer;
    let agrichainContract;

    // --- CẤU HÌNH GIAO DIỆN THÔNG BÁO TRƯỢT (TOAST) ---
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end', // Trượt ra từ góc trên bên phải
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        customClass: {
            popup: 'bg-white rounded-xl shadow-lg border border-gray-100 text-sm font-medium',
            title: 'text-gray-800'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    async function initWallet() {
        if (typeof window.ethereum !== 'undefined') {
            try {
                provider = new ethers.providers.Web3Provider(window.ethereum);
                
                window.ethereum.on('accountsChanged', function (accounts) {
                    window.location.reload(); 
                });
                window.ethereum.on('chainChanged', function (chainId) {
                    window.location.reload(); 
                });

                const accounts = await provider.listAccounts();
                if (accounts.length > 0) {
                    signer = provider.getSigner();
                    agrichainContract = new ethers.Contract(contractAddress, contractABI, signer);
                    const userAddress = await signer.getAddress();
                    
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

                    const workspace = document.getElementById('admin-workspace');
                    const accessDenied = document.getElementById('access-denied');

                    if(roleCode == 1) {
                        workspace.classList.remove('hidden');
                        accessDenied.classList.add('hidden');
                    } else {
                        workspace.classList.add('hidden');
                        accessDenied.classList.remove('hidden');
                    }
                } else {
                    document.getElementById('admin-workspace').classList.add('hidden');
                    document.getElementById('access-denied').classList.remove('hidden');
                }
            } catch (error) { console.error("Lỗi:", error); }
        }
    }

    async function handleAssignRole() {
        const targetAddress = document.getElementById('target-address').value.trim();
        const roleCode = document.getElementById('target-role').value;

        if(!targetAddress || targetAddress.length !== 42) {
            Toast.fire({ icon: 'warning', title: 'Địa chỉ ví không hợp lệ (cần 42 ký tự)' });
            return;
        }

        try {
            const btn = document.getElementById('btn-assign');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Đang chờ ví xác nhận...';
            btn.disabled = true;

            const tx = await agrichainContract.assignRole(targetAddress, roleCode);
            
            btn.innerHTML = '<i class="fa-solid fa-link fa-spin mr-2"></i>Đang ghi vào Blockchain...';
            await tx.wait(); 

            // HIỂN THỊ THÔNG BÁO TRƯỢT GÓC
            Toast.fire({ icon: 'success', title: 'Đã đóng dấu chuỗi thành công!' });

            btn.innerHTML = originalText;
            btn.disabled = false;
            document.getElementById('target-address').value = ""; 
            
        } catch (error) {
            console.error("Lỗi cấp quyền:", error);
            Toast.fire({ icon: 'error', title: 'Giao dịch bị từ chối! Bạn không phải Admin.' });
            
            const btn = document.getElementById('btn-assign');
            btn.innerHTML = '<i class="fa-solid fa-stamp mr-2"></i>Đóng dấu & Ký xác nhận lên Blockchain';
            btn.disabled = false;
        }
    }

    async function handleCheckRole() {
        const address = document.getElementById('check-address').value.trim();
        if(!address || address.length !== 42) {
            Toast.fire({ icon: 'warning', title: 'Vui lòng nhập địa chỉ ví hợp lệ!' });
            return;
        }
        
        try {
            const tempProvider = new ethers.providers.Web3Provider(window.ethereum);
            const tempContract = new ethers.Contract(contractAddress, contractABI, tempProvider);
            
            const code = await tempContract.userRoles(address);
            let roleName = "⚪ Ví thường";
            if (code == 1) roleName = "🔴 Admin";
            if (code == 2) roleName = "🟢 Nông dân";
            if (code == 3) roleName = "🔵 Kiểm định viên";
            if (code == 4) roleName = "🟠 Vận chuyển";

            // Bảng tra cứu vẫn nên giữ ở giữa màn hình vì chứa nhiều thông tin
            Swal.fire({
                title: '🔍 Kết quả tra cứu',
                html: `
                    <div class="text-left mt-4 text-sm space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="font-mono text-sm text-blue-600 break-all">${address}</p>
                        <p class="font-bold text-gray-800 text-lg">${roleName}</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: '#1f2937',
                confirmButtonText: 'Đóng'
            });
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'Không thể tra cứu thông tin!' });
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        initWallet();
        
        const btnAssign = document.getElementById('btn-assign');
        if(btnAssign) btnAssign.addEventListener('click', handleAssignRole);

        const btnCheck = document.getElementById('btn-check');
        if(btnCheck) btnCheck.addEventListener('click', handleCheckRole);
    });
</script>

<?php include 'footer.php'; ?>