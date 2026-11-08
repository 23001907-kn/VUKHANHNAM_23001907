const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })

export const currency = value => formatter.format(value)
