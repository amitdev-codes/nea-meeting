// Fallback for empty data
const meetingsPerMonthLabels = window.meetingsPerMonthLabels || [];
const meetingsPerMonthData = window.meetingsPerMonthData || [];
const statusLabels = window.statusLabels || ['No Data'];
const statusData = window.statusData || [0];
const userMeetingsPerDayLabels = window.userMeetingsPerDayLabels || [];
const userMeetingsPerDayData = window.userMeetingsPerDayData || [];

// Meetings Per Day Chart for User
if (document.getElementById('userMeetingsChart')) {
    new Chart(document.getElementById('userMeetingsChart'), {
        type: 'bar',
        data: {
            labels: userMeetingsPerDayLabels.length ? userMeetingsPerDayLabels : ['No Data'],
            datasets: [{
                label: 'बैठकहरूको संख्या',
                data: userMeetingsPerDayData.length ? userMeetingsPerDayData : [0],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            maintainAspectRatio: false,
            responsive: true
        }
    });
}

// Meeting Status Chart
if (document.getElementById('statusChart')) {
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true
        }
    });
}

// Admin Meetings Per Month Chart
if (document.getElementById('meetingsChart')) {
    new Chart(document.getElementById('meetingsChart'), {
        type: 'bar',
        data: {
            labels: meetingsPerMonthLabels.length ? meetingsPerMonthLabels : ['No Data'],
            datasets: [{
                label: 'Meetings Per Month',
                data: meetingsPerMonthData.length ? meetingsPerMonthData : [0],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            maintainAspectRatio: false,
            responsive: true
        }
    });
}
