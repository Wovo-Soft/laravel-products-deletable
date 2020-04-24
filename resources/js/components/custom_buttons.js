export default function () {
    return [
        {
            text: 'Products',
            to: {name: 'ProductsList'},
            variant: 'outline-dark',
        },
        {
            text: 'Categories',
            to: {name: 'ProductCategories'},
            variant: 'outline-dark',
            size: 'sm'
        },
        {
            text: 'Brands',
            to: {name: 'ProductBrands'},
            variant: 'outline-dark',
            size: 'sm'
        }
    ]
}
