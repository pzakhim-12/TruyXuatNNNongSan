// config.js — Cấu hình Smart Contract dùng chung cho toàn bộ dự án AgriChain
// Đổi contract (deploy lại / chuyển máy khác) chỉ cần sửa DUY NHẤT file này,
// không cần lục lại từng trang index.php / admin.php / danh-sach.php nữa.

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
