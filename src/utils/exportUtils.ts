import ExcelJS from 'exceljs';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// Export to Excel
export async function exportToExcel(data: any[], filename: string, sheetName: string = 'Data') {
  const workbook = new ExcelJS.Workbook();
  const worksheet = workbook.addWorksheet(sheetName);

  if (data.length === 0) {
    throw new Error('No data to export');
  }

  // Get headers from first object
  const headers = Object.keys(data[0]);
  
  // Add header row with styling
  worksheet.addRow(headers);
  worksheet.getRow(1).font = { bold: true, size: 12 };
  worksheet.getRow(1).fill = {
    type: 'pattern',
    pattern: 'solid',
    fgColor: { argb: 'FF15803D' }, // toba-green
  };
  worksheet.getRow(1).font = { ...worksheet.getRow(1).font, color: { argb: 'FFFFFFFF' } };

  // Add data rows
  data.forEach(item => {
    const row = headers.map(header => item[header]);
    worksheet.addRow(row);
  });

  // Auto-fit columns
  worksheet.columns.forEach(column => {
    let maxLength = 0;
    column.eachCell?.({ includeEmpty: true }, cell => {
      const columnLength = cell.value ? String(cell.value).length : 10;
      if (columnLength > maxLength) {
        maxLength = columnLength;
      }
    });
    column.width = maxLength < 10 ? 10 : maxLength + 2;
  });

  // Generate buffer
  const buffer = await workbook.xlsx.writeBuffer();
  
  // Download file
  const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
  const url = window.URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `${filename}.xlsx`;
  link.click();
  window.URL.revokeObjectURL(url);
}

// Export to PDF
export function exportToPDF(data: any[], filename: string, title: string = 'Report') {
  const doc = new jsPDF();

  if (data.length === 0) {
    throw new Error('No data to export');
  }

  // Add title
  doc.setFontSize(18);
  doc.setFont('helvetica', 'bold');
  doc.text(title, 14, 20);

  // Add date
  doc.setFontSize(10);
  doc.setFont('helvetica', 'normal');
  doc.text(`Generated: ${new Date().toLocaleDateString('id-ID')}`, 14, 28);

  // Get headers and rows
  const headers = Object.keys(data[0]);
  const rows = data.map(item => headers.map(header => item[header]));

  // Add table
  autoTable(doc, {
    head: [headers],
    body: rows,
    startY: 35,
    theme: 'grid',
    headStyles: {
      fillColor: [21, 128, 61], // toba-green
      textColor: [255, 255, 255],
      fontStyle: 'bold',
      fontSize: 10,
    },
    bodyStyles: {
      fontSize: 9,
    },
    alternateRowStyles: {
      fillColor: [245, 245, 245],
    },
    margin: { top: 35 },
  });

  // Save PDF
  doc.save(`${filename}.pdf`);
}

// Export to CSV
export function exportToCSV(data: any[], filename: string) {
  if (data.length === 0) {
    throw new Error('No data to export');
  }

  const headers = Object.keys(data[0]);
  const csvContent = [
    headers.join(','),
    ...data.map(item => 
      headers.map(header => {
        const value = item[header];
        // Escape commas and quotes
        if (typeof value === 'string' && (value.includes(',') || value.includes('"'))) {
          return `"${value.replace(/"/g, '""')}"`;
        }
        return value;
      }).join(',')
    )
  ].join('\n');

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = window.URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `${filename}.csv`;
  link.click();
  window.URL.revokeObjectURL(url);
}

// Format data for export
export function formatDataForExport(data: any[], type: 'bookings' | 'packages' | 'users' | 'finance') {
  switch (type) {
    case 'bookings':
      return data.map(item => ({
        'ID': item.id,
        'Customer': item.customerName || item.customer_name,
        'Email': item.customerEmail || item.customer_email,
        'Phone': item.customerPhone || item.customer_phone,
        'Package/Item': item.itemName || item.item_name,
        'Start Date': item.startDate || item.start_date,
        'End Date': item.endDate || item.end_date,
        'Total Price': item.totalPrice || item.total_price,
        'Status': item.status,
        'Created': item.createdAt || item.created_at,
      }));

    case 'packages':
      return data.map(item => ({
        'ID': item.id,
        'Name': item.name,
        'Category': item.isOutbound ? 'Outbound' : 'Tour',
        'Price': item.price,
        'Duration': item.duration,
        'Status': item.status,
        'Featured': item.isFeatured ? 'Yes' : 'No',
        'Created': item.createdAt,
      }));

    case 'users':
      return data.map(item => ({
        'ID': item.id,
        'Name': item.name,
        'Email': item.email,
        'Phone': item.phone,
        'Role': item.role,
        'Created': item.createdAt,
      }));

    case 'finance':
      return data.map(item => ({
        'Date': item.date,
        'Description': item.description,
        'Type': item.type,
        'Amount': item.amount,
        'Status': item.status,
        'Reference': item.reference,
      }));

    default:
      return data;
  }
}
