// Shipping Status
// Shipping Service Status

// Shipping API - Indonesia Province/State
describe('RajaOngkir Province/State API', () => {
    context('GET /province', () => {
        it('should return a list of province', () => {
            cy.request({
                method: 'GET',
                url: 'http://lokuswp.local/wp-json/lwcommerce/v1/rajaongkir/province'
            })
                .should((response) => {
                    cy.log(JSON.stringify(response.body))
                    expect(response.status).to.eq(200)
                    expect(response.body.data.length).to.be.eq(34);
                    expect(response.body.data[0]).to.have.all.keys(
                        'province_id', 'province'
                    )
                });
        });
    });
});

// Shipping API - Indonesia City
describe.only('RajaOngkir City API', () => {
    context('GET /city', () => {
        it('should return a list of city of Banten', () => {
            cy.request({
                method: 'GET',
                url: 'http://lokuswp.local/wp-json/lwcommerce/v1/rajaongkir/city?province=3'
            })
                .should((response) => {
                    cy.log(JSON.stringify(response.body))
                    expect(response.status).to.eq(200)
                    expect(response.body.data[0]).to.have.all.keys(
                        'city_id', 'province_id', 'province', 'type', 'city_name', 'postal_code'
                    )
                });
        });
    });
});

// Shipping API - RajaOngkir Cost Calculation
// describe('RajaOngkir Shipping Calculation API', () => {
// });