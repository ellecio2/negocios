window.addEventListener("DOMContentLoaded", async () => {
    $("#seller_total_price").val(0);
    const inputs = {
        unitPrice: document.querySelector('input[name="unit_price"]'),
        reSellerCommission: document.querySelector(
            'input[id="re_seller_commission"]'
        ),
        store: {
            unitPrice: document.querySelector("#store_unit_price"),
            vendorCommission: document.querySelector(
                "#store_vendor_commission"
            ),
            totalPrice: document.querySelector("#store_total_price"),
            commissionPercent: document.querySelector(
                "#store_comission_percent"
            ),
            revenue: document.querySelector("#store_revenue"),
        },
        reSeller: {
            unitPrice: document.querySelector("#seller_unit_price"),
            commissionPercent: document.querySelector(
                "#seller_commission_percent"
            ),
            commission: document.querySelector("#seller_commission"),
            vendorCommissionPercent: document.querySelector(
                "#seller_vendor_comission_percent"
            ),
            vendorCommission: document.querySelector(
                "#seller_vendor_commission"
            ),
            totalPrice: document.querySelector("#seller_total_price"),
            revenue: document.querySelector("#seller_revenue"),
        },
    };

    const vendorCommission = await getVendorComission();
    inputs.store.commissionPercent.textContent = `${vendorCommission}%`;
    inputs.reSeller.commissionPercent.textContent = `${vendorCommission}%`;

    calcTotalPrices(inputs, vendorCommission);

    inputs.unitPrice.addEventListener("input", () =>
        calcTotalPrices(inputs, vendorCommission)
    );
    inputs.reSellerCommission.addEventListener("input", () =>
        calcTotalPrices(inputs, vendorCommission)
    );
});

async function getVendorComission() {
    const response = await fetch(
        `${url}/api/v2/business-settings?type=vendor_commission`,
        {
            method: "GET",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        }
    );

    const res = await response.json();

    if (res.result === true) {
        return parseInt(res.data);
    }

    return false;
}

function calcTotalPrices(inputs, vendorCommission) {
    calcStoreTotalPrices(
        inputs.store,
        inputs.unitPrice.value,
        vendorCommission
    );
    calcResellerTotalPrices(
        inputs.reSeller,
        inputs.unitPrice.value,
        inputs.reSellerCommission.value,
        vendorCommission
    );
}

const formatter = new Intl.NumberFormat('es-DO', {
    style: 'currency',
    currency: 'DOP',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

function calcStoreTotalPrices(storeInputs, unitPrice, commissionPercent) {
    const commission = ((commissionPercent / 100) * unitPrice).toFixed(2);
    const price = (unitPrice * 1).toFixed(2);
    const revenue = (unitPrice * (1 - commissionPercent / 100)).toFixed(2);

    storeInputs.vendorCommission.textContent = `- ${formatter.format(commission)}`;
    storeInputs.unitPrice.textContent = `${formatter.format(unitPrice)}`;
    storeInputs.totalPrice.value = `${formatter.format(price)}`;
    storeInputs.revenue.value = `${formatter.format(revenue)}`;
}

function calcResellerTotalPrices(
    reSellerInputs,
    unitPrice,
    reSellerCommissionValue,
    vendorCommission
) {
    unitPrice = parseFloat(unitPrice);
    const commissionPercent = vendorCommission / 100;
    const commission = parseFloat((commissionPercent * unitPrice).toFixed(2));

    const reSellerCommisionPercent = reSellerCommissionValue / 100;
    let reSellerCommission = parseFloat(
        (reSellerCommisionPercent * unitPrice).toFixed(2)
    );
    if (isNaN(reSellerCommission)) {
        reSellerCommission = 0.0;
    }

    let price = (unitPrice + reSellerCommission).toFixed(2) ?? "0.00";
    if (isNaN(price)) {
        price = "0.00";
    }
    reSellerInputs.vendorCommissionPercent.textContent = `${formatter.format(vendorCommission)}%`;
    //reSellerInputs.vendorCommission.textContent = `+ RD$ ${commission}`;

    reSellerInputs.commissionPercent.textContent = `${formatter.format(reSellerCommissionValue)}%`;
    reSellerInputs.commission.textContent = `+ ${formatter.format(reSellerCommission)}`;

    reSellerInputs.unitPrice.textContent = `${formatter.format(unitPrice)}`;
    reSellerInputs.totalPrice.value = `${formatter.format(price)}`;
    reSellerInputs.revenue.value = `${
        formatter.format(price - reSellerCommission - commission)
    }`;
}
