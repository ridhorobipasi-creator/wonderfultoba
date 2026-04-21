import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

export const generateInvoice = (booking: any, preview: boolean = false) => {
  const doc = new jsPDF();
  
  // Format Date
  const invoiceDate = new Date(booking.createdAt || Date.now()).toLocaleDateString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric'
  });
  const startDate = new Date(booking.startDate).toLocaleDateString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric'
  });
  const endDate = new Date(booking.endDate).toLocaleDateString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric'
  });

  // Header Title
  doc.setFontSize(22);
  doc.setTextColor(15, 118, 110); // toba-green
  doc.text('INVOICE / TAGIHAN', 14, 25);
  
  // Company Info
  doc.setFontSize(10);
  doc.setTextColor(100, 100, 100);
  doc.text('Wonderful Toba', 14, 35);
  doc.text('Jl. Sisingamangaraja No. 12, Medan', 14, 40);
  doc.text('Phone: 0813-2388-8207', 14, 45);

  // Invoice Details
  doc.setTextColor(0, 0, 0);
  doc.text(`No. Invoice: INV-WT-${booking.id?.toString().padStart(4, '0')}`, 140, 35);
  doc.text(`Tanggal: ${invoiceDate}`, 140, 40);
  doc.text(`Status: ${booking.status?.toUpperCase()}`, 140, 45);

  // Customer Info
  doc.setFontSize(12);
  doc.setTextColor(15, 118, 110);
  doc.text('Ditagihkan Kepada:', 14, 60);
  
  doc.setFontSize(10);
  doc.setTextColor(0, 0, 0);
  doc.text(booking.customerDetails?.name || booking.customerName || 'Customer', 14, 67);
  doc.text(booking.customerDetails?.email || booking.customerEmail || '-', 14, 72);
  doc.text(booking.customerDetails?.phone || booking.customerPhone || '-', 14, 77);

  // Table
  autoTable(doc, {
    startY: 90,
    head: [['Deskripsi Layanan', 'Tipe', 'Tanggal Pelaksanaan', 'Jumlah']],
    body: [
      [
        booking.itemName || 'Layanan Wonderful Toba',
        booking.type?.toUpperCase() || '-',
        `${startDate} s/d ${endDate}`,
        `Rp ${(booking.totalPrice || 0).toLocaleString('id-ID')}`
      ]
    ],
    theme: 'grid',
    headStyles: { fillColor: [15, 118, 110] }, // toba-green
    styles: { fontSize: 10, cellPadding: 5 },
  });

  // Total
  const finalY = (doc as any).lastAutoTable.finalY || 120;
  doc.setFontSize(12);
  doc.setFont('helvetica', 'bold');
  doc.text('TOTAL PEMBAYARAN:', 110, finalY + 15);
  doc.text(`Rp ${(booking.totalPrice || 0).toLocaleString('id-ID')}`, 155, finalY + 15);

  // Footer notes
  doc.setFontSize(9);
  doc.setFont('helvetica', 'normal');
  doc.setTextColor(150, 150, 150);
  doc.text('Terima kasih telah mempercayai Wonderful Toba untuk perjalanan Anda.', 14, finalY + 35);
  doc.text('Silakan hubungi WhatsApp kami (+62 813-2388-8207) jika ada pertanyaan.', 14, finalY + 40);

  if (preview) {
    const blobUrl = doc.output('bloburl');
    window.open(blobUrl, '_blank');
  } else {
    doc.save(`Invoice_WT_${booking.id}_${booking.customerDetails?.name || booking.customerName}.pdf`);
  }
};
