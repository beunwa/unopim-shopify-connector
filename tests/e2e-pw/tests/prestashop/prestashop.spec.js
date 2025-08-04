import { test, expect } from '@playwright/test';

test.use({ storageState: 'storage/auth.json' }); // Reuse login session

test.describe('UnoPim PrestaShop Plugin Navigation', () => {
    test('should navigate to PrestaShop credentials page', async ({ page }) => {
        // Go directly to the admin dashboard (User is already logged in)
        await page.goto('/admin/dashboard');

        // Click the PrestaShop plugin link
        const prestashopLink = page.getByRole('link', { name: /PrestaShop/i });
        await prestashopLink.click();
        // Verify navigation to the PrestaShop credentials page
        await expect(page).toHaveURL('http://localhost:8000/admin/prestashop/credentials');

        // Verify the PrestaShop icon and text are visible
        await expect(page.locator('.icon-prestashop')).toBeVisible();
        await expect(prestashopLink.locator('text=PrestaShop')).toBeVisible();
        await expect(page).toHaveTitle(/PrestaShop/i);

    });
});
