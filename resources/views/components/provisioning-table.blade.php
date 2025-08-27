{{-- resources/views/components/provisioning-table.blade.php --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 text-primary">
                <i class="fas fa-table me-2"></i>
                Provisioning Data
            </h5>
            <div class="table-controls d-flex align-items-center">
                <label for="perPage" class="form-label me-2 mb-0 text-muted">Show:</label>
                <select class="form-select form-select-sm" id="perPage" style="width: auto;">
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                </select>
                <span class="text-muted ms-2">entries</span>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="provisioningTable">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">
                            Property Name
                        </th>
                        <th scope="col">
                            Property Type
                        </th>
                        <th scope="col">
                            Property Address
                        </th>
                        <th scope="col">
                            System Type
                        </th>
                        <th scope="col">
                            Master Unit Quantity
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    {{-- Dummy Data Rows --}}
                    @php
                        $dummyData = [
                            ['Sunset Plaza', 'Residential', '123 Sunset Blvd, Los Angeles, CA', 'Fiber Optic', '150'],
                            ['Tech Tower', 'Commercial', '456 Tech Street, San Francisco, CA', 'Copper', '300'],
                            ['Green Valley Apartments', 'Residential', '789 Valley Road, Phoenix, AZ', 'Fiber Optic', '200'],
                            ['Downtown Office Complex', 'Commercial', '321 Business Ave, New York, NY', 'Hybrid', '500'],
                            ['Riverside Condos', 'Residential', '654 River Lane, Miami, FL', 'Fiber Optic', '120'],
                            ['Central Business Hub', 'Commercial', '987 Central Plaza, Chicago, IL', 'Copper', '400'],
                            ['Mountain View Estates', 'Residential', '147 Mountain Dr, Denver, CO', 'Fiber Optic', '180'],
                            ['Innovation Center', 'Commercial', '258 Innovation Way, Austin, TX', 'Hybrid', '250'],
                            ['Lakeside Towers', 'Residential', '369 Lake Shore Dr, Seattle, WA', 'Fiber Optic', '220'],
                            ['Metro Square', 'Commercial', '741 Metro Blvd, Boston, MA', 'Copper', '350'],
                            ['Palm Gardens', 'Residential', '852 Palm Street, San Diego, CA', 'Fiber Optic', '160'],
                            ['Corporate Plaza', 'Commercial', '963 Corporate Dr, Atlanta, GA', 'Hybrid', '450'],
                            ['Oceanview Apartments', 'Residential', '174 Ocean Ave, Portland, OR', 'Fiber Optic', '190'],
                            ['Business District Center', 'Commercial', '285 District St, Dallas, TX', 'Copper', '380'],
                            ['Hillcrest Homes', 'Residential', '396 Hill Road, Nashville, TN', 'Fiber Optic', '140'],
                            ['Technology Park', 'Commercial', '507 Tech Park Dr, Raleigh, NC', 'Hybrid', '320'],
                            ['Meadow Brook', 'Residential', '618 Meadow Lane, Kansas City, MO', 'Fiber Optic', '170'],
                            ['Executive Suites', 'Commercial', '729 Executive Blvd, Tampa, FL', 'Copper', '280'],
                            ['Garden City Complex', 'Residential', '840 Garden Way, Salt Lake City, UT', 'Fiber Optic', '210'],
                            ['Professional Center', 'Commercial', '951 Professional Dr, Minneapolis, MN', 'Hybrid', '420'],
                        ];
                    @endphp
                    
                    @foreach($dummyData as $index => $row)
                        <tr class="table-row" data-index="{{ $index + 1 }}">
                            <td>{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                            <td>{{ $row[3] }}</td>
                            <td>{{ number_format($row[4]) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="table-info">
                <span class="text-muted">
                    Showing <span id="showingStart">1</span> to <span id="showingEnd">20</span> of <span id="totalEntries">20</span> entries
                </span>
            </div>
            
            <nav aria-label="Table pagination">
                <ul class="pagination pagination-sm mb-0" id="pagination">
                    <li class="page-item disabled" id="prevBtn">
                        <a class="page-link" href="#" tabindex="-1">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active" data-page="1">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item" id="nextBtn">
                        <a class="page-link" href="#">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
.table-responsive {
    border-radius: 0;
}

.table th {
    background-color: #13395d;
    color: white;
    font-weight: 600;
    border: none;
    padding: 15px 12px;
    vertical-align: middle;
}

.table td {
    padding: 12px;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.table-row {
    /* No animations or hover effects */
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
}

.pagination .page-link {
    border: 1px solid #e9ecef;
    color: #667eea;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table th, .table td {
        padding: 8px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .table-info {
        order: 2;
        text-align: center;
    }
    
    .pagination {
        order: 1;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const perPageSelect = document.getElementById('perPage');
    const tableBody = document.getElementById('tableBody');
    const showingStart = document.getElementById('showingStart');
    const showingEnd = document.getElementById('showingEnd');
    const totalEntries = document.getElementById('totalEntries');
    const pagination = document.getElementById('pagination');
    
    let currentPage = 1;
    let rowsPerPage = 20;
    
    // Create all table rows data (convert HTML to data)
    const allRowsData = Array.from(tableBody.querySelectorAll('.table-row')).map(row => {
        return Array.from(row.children).map(cell => cell.textContent.trim());
    });
    const totalRows = allRowsData.length;
    
    // Initialize
    updateTable();
    
    // Handle rows per page change
    perPageSelect.addEventListener('change', function() {
        rowsPerPage = parseInt(this.value);
        currentPage = 1;
        updateTable();
    });
    
    function updateTable() {
        // Clear current table
        tableBody.innerHTML = '';
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, totalRows);
        const visibleRowsData = allRowsData.slice(startIndex, endIndex);
        
        // Add visible rows to table
        visibleRowsData.forEach(rowData => {
            // Create new row element with data
            const row = document.createElement('tr');
            row.className = 'table-row';
            row.innerHTML = `
                <td>${rowData[0]}</td>
                <td>${rowData[1]}</td>
                <td>${rowData[2]}</td>
                <td>${rowData[3]}</td>
                <td>${rowData[4]}</td>
            `;
            tableBody.appendChild(row);
        });
        
        // Update showing info
        showingStart.textContent = totalRows > 0 ? startIndex + 1 : 0;
        showingEnd.textContent = endIndex;
        totalEntries.textContent = totalRows;
        
        // Update pagination
        updatePagination();
    }
    
    function updatePagination() {
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        pagination.innerHTML = '';
        
        // Previous button
        const prevBtn = createPageItem('prev', '<i class="fas fa-chevron-left"></i>');
        prevBtn.classList.toggle('disabled', currentPage === 1);
        pagination.appendChild(prevBtn);
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        if (startPage > 1) {
            pagination.appendChild(createPageItem(1, '1'));
            if (startPage > 2) {
                const ellipsis = document.createElement('li');
                ellipsis.className = 'page-item disabled';
                ellipsis.innerHTML = '<span class="page-link">...</span>';
                pagination.appendChild(ellipsis);
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageItem = createPageItem(i, i.toString());
            pageItem.classList.toggle('active', i === currentPage);
            pagination.appendChild(pageItem);
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement('li');
                ellipsis.className = 'page-item disabled';
                ellipsis.innerHTML = '<span class="page-link">...</span>';
                pagination.appendChild(ellipsis);
            }
            pagination.appendChild(createPageItem(totalPages, totalPages.toString()));
        }
        
        // Next button
        const nextBtn = createPageItem('next', '<i class="fas fa-chevron-right"></i>');
        nextBtn.classList.toggle('disabled', currentPage === totalPages);
        pagination.appendChild(nextBtn);
    }
    
    function createPageItem(page, text) {
        const li = document.createElement('li');
        li.className = 'page-item';
        
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.innerHTML = text;
        
        a.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (li.classList.contains('disabled') || li.classList.contains('active')) {
                return;
            }
            
            if (page === 'prev') {
                currentPage = Math.max(1, currentPage - 1);
            } else if (page === 'next') {
                currentPage = Math.min(Math.ceil(totalRows / rowsPerPage), currentPage + 1);
            } else {
                currentPage = page;
            }
            
            updateTable();
        });
        
        li.appendChild(a);
        return li;
    }
});
</script>