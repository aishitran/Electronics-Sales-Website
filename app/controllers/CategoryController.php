<?php
require_once 'app/models/CategoryModel.php';

class CategoryController 
{
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Used by: 
     * - app/views/admin/admin_categories.php
     * - app/views/admin/admin_panel.php (when accessing categories section)
     * - app/views/layout/header.php (when accessing categories from navigation)
     * Displays all categories in the admin panel
     */
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

    /**
     * Used by: 
     * - app/views/product/category_view.php
     * - app/views/layout/header.php (when clicking on a category)
     * - app/views/home/home.php (when clicking on a category)
     * Displays products in a specific category with optional sorting and filtering
     */
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
    
    /**
     * Used by: 
     * - app/views/admin/category_create.php
     * - app/views/admin/admin_panel.php (when creating a new category)
     * Handles the creation of a new category
     */
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

    /**
     * Used by: 
     * - app/views/admin/category_edit.php
     * - app/views/admin/admin_panel.php (when editing a category)
     * Handles the editing of an existing category
     */
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
                header('Location: /index.php?action=adminPanel&section=categories');
            } else {
                $_SESSION['error'] = 'Cập nhật danh mục thất bại!';
                header('Location: /index.php?action=editCategory&id=' . $id);
            }
            exit;
        }
        $category = $this->categoryModel->getCategoryById($id);
        require_once 'app/views/admin/category_edit.php';
    }

    /**
     * Used by: 
     * - app/views/admin/admin_categories.php
     * - app/views/admin/admin_panel.php (when deleting a category)
     * Handles the deletion of a category
     */
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
        header('Location: /index.php?action=adminPanel&section=categories');
        exit;
    }
}