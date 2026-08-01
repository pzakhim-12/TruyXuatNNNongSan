// BIẾN TRẠNG THÁI VÍ (dùng chung, các trang gán/đọc lại cùng 4 biến này)

let provider;
let signer;
let agrichainContract;
let userAddress = "";


// Rút gọn địa chỉ ví dạng 0x82b...1577 — dùng chung để tránh lặp code
// (và tránh lặp lại lỗi cắt sai ký tự do gõ tay substring nhiều nơi)

function formatShortAddress(address) {
    if (!address || address.length < 10) return address;
    return address.substring(0, 5) + '...' + address.substring(38);
}


// Bản đồ roleCode -> {tên hiển thị, màu nút} dùng chung cho navbar mọi trang

const ROLE_MAP = {
    0: { name: "Chưa phân quyền", color: "bg-gray-600" },
    1: { name: "Admin", color: "bg-red-600" },
    2: { name: "Nông dân", color: "bg-green-600" },
    3: { name: "Kiểm định", color: "bg-blue-600" },
    4: { name: "Vận chuyển", color: "bg-amber-600" }
};

function getRoleInfo(roleCode) {
    return ROLE_MAP[roleCode] || ROLE_MAP[0];
}

// Cập nhật nút ví trên navbar (header.php) — dùng chung cho mọi trang
function updateWalletNavbar(address, roleCode) {
    const { name, color } = getRoleInfo(roleCode);
    const shortAddress = formatShortAddress(address);
    const btnConnect = document.getElementById("btnConnect");
    const addressDisplay = document.getElementById("walletAddressDisplay");
    if (btnConnect && addressDisplay) {
        btnConnect.className = `${color} hover:opacity-80 text-white font-semibold px-4 py-2 rounded-xl text-sm shadow-md transition flex items-center space-x-2`;
        addressDisplay.innerText = `${shortAddress} (${name})`;
    }
}


// Cấu hình toast thông báo trượt góc (SweetAlert2)

const Toast = typeof Swal !== 'undefined' ? Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    customClass: {
        popup: 'bg-white rounded-xl shadow-lg border border-gray-100 text-sm font-medium',
        title: 'text-gray-800'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
}) : null;


// Quét event theo từng đợt (chunk) để không bỏ sót lô hàng cũ hơn giới hạn
// RPC free-tier (~9000 block/lần). Lùi dần từng đợt cho tới khi hết lịch sử
// hoặc chạm maxChunks.

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

    allEvents.sort((a, b) => a.blockNumber - b.blockNumber || a.logIndex - b.logIndex);
    return allEvents;
}


// Kết nối ví thủ công (hiện popup MetaMask xin quyền). Dùng chung vì nút
// "Kết nối ví" nằm trong header.php nên xuất hiện ở MỌI trang.

async function connectWallet() {
    if (typeof window.ethereum !== 'undefined') {
        try {
            provider = new ethers.providers.Web3Provider(window.ethereum);
            await provider.send("eth_requestAccounts", []);
            signer = provider.getSigner();
            agrichainContract = new ethers.Contract(contractAddress, contractABI, signer);
            userAddress = await signer.getAddress();

            const roleCode = await agrichainContract.userRoles(userAddress);
            updateWalletNavbar(userAddress, roleCode);

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
