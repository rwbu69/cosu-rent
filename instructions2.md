## Phase 2: UI/UX Revamp & Security Access Audit

**Objective:** Overhaul the entire application layout to achieve a highly professional, enterprise-grade aesthetic while strictly maintaining the flat design system and the `#F2B3BD` / `#FCF0F2` color palette. Follow up with a strict verification of Role-Based Access Control (RBAC).

### 1. UI/UX Restructuring & Professional Layouts
Stop using single monolithic view files. You MUST restructure the frontend using Laravel Blade Components (`<x-layout.admin>`, `<x-layout.public>`) to ensure consistency.

**A. Admin Dashboard Layout (`<x-layout.admin>`)**
- **Structure:** Implement a classic, professional Sidebar + Topbar layout.
- **Sidebar:** Fixed on the left. Use a very dark slate gray (e.g., `bg-slate-900`) for the sidebar background to create a strong professional contrast against the pink application content. Use `#F2B3BD` strictly for the active menu item highlight (left-border or flat background highlight).
- **Topbar:** Clean white or `#FCF0F2` background, containing breadcrumbs, the user's profile dropdown, and a quick-scan hardware input status indicator.
- **Main Content Area:** Use a subtle off-white or `#FCF0F2` background. Wrap all tables and forms inside crisp, white card components (`bg-white border border-gray-200 rounded-none or rounded-sm`).

**B. Public/Customer Layout (`<x-layout.public>`)**
- **Structure:** Implement a modern Top Navbar + Footer layout.
- **Navbar:** Sticky top, utilizing a solid white background with crisp `#F2B3BD` buttons for "Login/Register" or "Sewa Sekarang".
- **Whitespace & Typography:** Increase padding heavily (`p-6` to `p-10`) across all container sections to let the content breathe. Use the `Inter` font with strict weight hierarchy (e.g., `font-black` for main headings, `font-medium` for subheadings, `font-normal text-gray-600` for paragraphs).
- **Color Application:** Do not overuse the pink colors. Use `#FCF0F2` for large section backgrounds (like the Hero section) and `#F2B3BD` exclusively for Call-to-Action (CTA) buttons, active tabs, and important icons.

### 2. Component Standardization
- **Forms & Inputs:** Standardize all form inputs to have flat borders (`border-gray-300`), sharp or slightly rounded corners, and a focus state that applies a solid `#F2B3BD` border ring (NO glows/shadows, use `focus:ring-0 focus:border-[#F2B3BD]`).
- **Data Tables:** Admin tables must be spacious. Add plenty of padding to table cells (`px-4 py-3`), use a subtle `#FCF0F2` for table headers, and include hover effects (`hover:bg-gray-50`) on table rows for better readability.

### 3. Role-Based Access Verification (Audit)
After applying the layout revamp, you must systematically verify that all views and routes are properly protected. 

**Execution Steps for the Agent:**
1. **Middleware Audit:** Ensure the `Route::group` for `/admin` explicitly uses the middleware that blocks the `customer` role. Ensure `/profil` and `/checkout` explicitly require authentication.
2. **View Logic Audit:** Check all Blade files for conditional rendering. If there is a shared component (like the Navbar), ensure `customer` links do not appear for `admin`, and vice versa.
3. **Simulate/Test Access:** - Write a quick Laravel Feature Test (PHPUnit/Pest) OR simulate the request logic to confirm:
     - A user with role `customer` requesting `GET /admin/dashboard` returns exactly a 403 status.
     - An unauthenticated user requesting `GET /checkout` redirects to the login page.
     - A user with role `admin` requesting `GET /admin/pesanan-rental` returns a 200 status and successfully loads the new `<x-layout.admin>` component.
4. **Output Report:** Print a final summary acknowledging the layout restructure is complete and confirming the results of the 403/200 access tests for both roles.
