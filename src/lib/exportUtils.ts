import ExcelJS from 'exceljs';
import { Booking } from '../types';

export const exportBookingsToExcel = async (bookings: Booking[]) => {
  const workbook = new ExcelJS.Workbook();
  const worksheet = workbook.addWorksheet('Reservasi');
  
  // Define columns
  worksheet.columns = [
    { header: 'ID Order', key: 'id', width: 15 },
    { header: 'Tanggal Dibuat', key: 'created_at', width: 20 },
    { header: 'Layanan', key: 'type', width: 15 },
    { header: 'Item Dipesan', key: 'item', width: 40 },
    { header: 'Mulai', key: 'start', width: 15 },
    { header: 'Selesai', key: 'end', width: 15 },
    { header: 'Nama Pelanggan', key: 'customer', width: 30 },
    { header: 'No. WhatsApp', key: 'phone', width: 20 },
    { header: 'Email', key: 'email', width: 30 },
    { header: 'Total Pembayaran (Rp)', key: 'total', width: 25 },
    { header: 'Status Reservasi', key: 'status', width: 15 },
  ];

  // Add formatting to header
  worksheet.getRow(1).font = { bold: true, color: { argb: 'FFFFFFFF' } };
  worksheet.getRow(1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0F766E' } };

  // Add data rows
  bookings.forEach(b => {
    worksheet.addRow({
      id: `INV-WT-${b.id?.toString().padStart(4, '0')}`,
      created_at: new Date(b.createdAt || Date.now()).toLocaleDateString('id-ID'),
      type: b.type?.toUpperCase(),
      item: b.itemName,
      start: b.startDate,
      end: b.endDate,
      customer: b.customerDetails?.name || '',
      phone: b.customerDetails?.phone || '',
      email: b.customerDetails?.email || '',
      total: b.totalPrice,
      status: b.status?.toUpperCase()
    });
  });

  // Export
  const buffer = await workbook.xlsx.writeBuffer();
  const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
  const url = window.URL.createObjectURL(blob);
  
  const link = document.createElement('a');
  link.href = url;
  // File namings
  const today = new Date().toISOString().split('T')[0];
  link.download = `Laporan_Reservasi_WT_${today}.xlsx`;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  window.URL.revokeObjectURL(url);
};
