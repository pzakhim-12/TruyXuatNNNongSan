// admin.js — Logic riêng cho trang admin.php (RBAC: cấp quyền / tra cứu quyền)
// Cần load SAU config.js và utils.js.

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
                userAddress = await signer.getAddress();

                const roleCode = await agrichainContract.userRoles(userAddress);
                updateWalletNavbar(userAddress, roleCode);

                const workspace = document.getElementById('admin-workspace');
                const accessDenied = document.getElementById('access-denied');

                if (roleCode == 1) {
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

    if (!targetAddress || targetAddress.length !== 42) {
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
    if (!address || address.length !== 42) {
        Toast.fire({ icon: 'warning', title: 'Vui lòng nhập địa chỉ ví hợp lệ!' });
        return;
    }

    try {
        const tempProvider = new ethers.providers.Web3Provider(window.ethereum);
        const tempContract = new ethers.Contract(contractAddress, contractABI, tempProvider);

        const code = await tempContract.userRoles(address);
        const roleIcons = { 0: "⚪ Ví thường", 1: "🔴 Admin", 2: "🟢 Nông dân", 3: "🔵 Kiểm định viên", 4: "🟠 Vận chuyển" };
        const roleName = roleIcons[code] || roleIcons[0];

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
    if (btnAssign) btnAssign.addEventListener('click', handleAssignRole);

    const btnCheck = document.getElementById('btn-check');
    if (btnCheck) btnCheck.addEventListener('click', handleCheckRole);
});
