let scheduleIndex = 1; // Start from 1 since we already have one schedule block

document.querySelector('button[type="button"]').addEventListener('click', function() {
    const container = document.getElementById('schedule-container');
    const newScheduleBlock = document.createElement('div');
    newScheduleBlock.classList.add('flex', 'gap-4', 'items-center', 'p-4', 'bg-slate-50', 'rounded-lg', 'border', 'border-slate-100');
    newScheduleBlock.innerHTML = `
        <select name="schedules[${scheduleIndex}][day_of_week]" class="rounded-lg border-slate-200">
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>
        <input type="time" name="schedules[${scheduleIndex}][start_time]" class="rounded-lg border-slate-200">
        <input type="time" name="schedules[${scheduleIndex}][end_time]" class="rounded-lg border-slate-200">
    `;
    container.appendChild(newScheduleBlock);
    scheduleIndex++;
});