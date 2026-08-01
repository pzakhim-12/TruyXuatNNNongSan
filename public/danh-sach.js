// danh-sach.js — Logic riêng cho trang danh-sach.php (Danh sách lô hàng)
// Cần load SAU config.js và utils.js.

// 1. Hàm tự động kết nối ví để giữ màu cho Navbar (giống bên index.php)
async function initWallet() {
    if (typeof window.ethereum !== 'undefined') {
        try {
            provider = new ethers.providers.Web3Provider(window.ethereum);
            const accounts = await provider.listAccounts();
            if (accounts.length > 0) {
                signer = provider.getSigner();
                userAddress = await signer.getAddress();
                const contract = new ethers.Contract(contractAddress, contractABI, signer);
                const roleCode = await contract.userRoles(userAddress);
                updateWalletNavbar(userAddress, roleCode);
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

        // Quét theo từng đợt (chunk) để không bỏ sót lô hàng cũ hơn 9000 block
        const filter = agrichainContract.filters.ProductCreated();
        const events = await queryEventsInChunks(agrichainContract, filter, provider);

        const tbody = document.getElementById('table-body');

        if (events.length === 0) {
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
            const shortWallet = formatShortAddress(farmerWallet);

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
    localStorage.setItem('searchId', id);
    window.location.href = 'index.php';
}

// Chạy các hàm khi tải trang xong
document.addEventListener("DOMContentLoaded", () => {
    initWallet();
    loadProducts();
});
