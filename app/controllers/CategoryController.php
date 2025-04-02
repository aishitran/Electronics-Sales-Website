<?php
require_once 'app/models/CategoryModel.php';

class CategoryController 
{
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        $categories = $this->categoryModel->getAllCategories();
        error_log("Categories fetched: " . print_r($categories, true));
        require_once 'app/views/admin/admin_categories.php';
    }

    public function viewCategory($id = null, $sort = '', $price = '') {
        $categories = $this->categoryModel->getAllCategories();
        
        if ($id) {
            $selectedCategory = $this->categoryModel->getCategoryById($id);
            $products = $this->categoryModel->getProductsByCategory($id, $sort, $price);
        } else {
            $selectedCategory = null;
            $products = [];
        }
        require_once 'app/views/product/category_view.php';
    }
    
    public function create() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenDanhMuc = $_POST['tenDanhMuc'];
            if ($this->categoryModel->createCategory($tenDanhMuc)) {
                $_SESSION['success'] = 'Thêm danh mục thành công!';
                header('Location: /index.php?action=adminCategories');
            } else {
                $_SESSION['error'] = 'Thêm danh mục thất bại!';
                header('Location: /index.php?action=createCategory');
            }
            exit;
        }
        require_once 'app/views/admin/category_create.php';
    }

    public function edit($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenDanhMuc = $_POST['tenDanhMuc'];
            if ($this->categoryModel->updateCategory($id, $tenDanhMuc)) {
                $_SESSION['success'] = 'Cập nhật danh mục thành công!';
                header('Location: /index.php?action=adminCategories');
            } else {
                $_SESSION['error'] = 'Cập nhật danh mục thất bại!';
                header('Location: /index.php?action=editCategory&id=' . $id);
            }
            exit;
        }
        $category = $this->categoryModel->getCategoryById($id);
        require_once 'app/views/admin/category_edit.php';
    }

    public function delete($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        if ($this->categoryModel->deleteCategory($id)) {
            $_SESSION['success'] = 'Xóa danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Xóa danh mục thất bại!';
        }
        header('Location: /index.php?action=adminCategories');
        exit;
    }
}